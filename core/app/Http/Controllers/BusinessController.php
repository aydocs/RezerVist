<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessApplication;
use App\Models\BusinessHour;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::where('is_active', true);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        return response()->json($query->paginate(20));
    }

    public function show(Business $business)
    {

        // Load relationships
        // Load relationships (Limit reviews to prevent payload issues)
        $business->load([
            'reviews' => function($q) {
                $q->latest()->limit(10)->with('user');
            },
            'resources', 
            'menus'
        ]);
        
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json($business)
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR);
        }

        $canReview = false;
        if (auth()->check()) {
            $canReview = \App\Models\Reservation::where('user_id', auth()->id())
                ->where('business_id', $business->id)
                ->where('status', 'completed')
                ->exists();
        }

        return view('business.show', compact('business', 'canReview'));
    }

    public function showApplyForm()
    {
        // Check if user has an active business or application
        if (Auth::check()) {
            $user = Auth::user();
            $businessesCount = $user->businesses()->count();
            $pendingAppsCount = BusinessApplication::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'under_review'])
                ->count();
            
            $totalActiveOrPending = $businessesCount + $pendingAppsCount;

            // Default limit for users with NO business yet (Free or just started)
            $limit = 1;

            // If user ALREADY has a business, we check the limit of that business's package
            // Note: In case of multiple businesses, we pick the most restrictive or use a system-wide user level limit if it existed.
            // Here we'll take the limit of any active business.
            foreach ($user->businesses as $business) {
                $bLimit = $business->getFeatureValue('business_limit', 1);
                if ($bLimit === -1) {
                    $limit = -1;
                    break;
                }
                $limit = max($limit, $bLimit);
            }

            if ($limit !== -1 && $totalActiveOrPending >= $limit) {
                $existingApp = BusinessApplication::where('user_id', Auth::id())->latest()->first();
                if ($existingApp && $existingApp->status === 'approved') {
                    return redirect()->route('dashboard')->with('info', 'Paketinizdeki işletme limitine ulaştınız.');
                } else if ($existingApp) {
                    return redirect()->route('business.application.status');
                }
            }
        }

        $categories = \App\Models\Category::all();
        $tags = \App\Models\Tag::all()->groupBy(function($item) {
            $slug = $item->slug;
            if (in_array($slug, ['wifi', 'otopark', 'vale', 'teras', 'bahce', 'cocuk-dostu', 'evcil-hayvan-dostu', 'engelli-uygun', 'kredi-karti', 'gel-al', 'paket-servis'])) return 'Özellikler';
            if (in_array($slug, ['italyan', 'uzak-dogu', 'ocakbasi', 'deniz-mahsulleri', 'vegan', 'vejetaryen', 'steakhouse', 'cafe', 'bar', 'meyhane'])) return 'Mutfak / Konsept';
            // Fallback for tags that might not be in the hardcoded lists
            return 'Diğer Özellikler';
        });
        
        return view('business.apply', compact('categories', 'tags'));
    }

    public function apply(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Başvuru yapmak için giriş yapmalısınız.');
        }

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'description' => 'required|string',
            'trade_registry_no' => 'required|string',
            'tax_id' => 'required|string',
            'trade_registry_document' => 'required|file|mimes:pdf,jpg,png|max:5120',
            'tax_document' => 'required|file|mimes:pdf,jpg,png|max:5120',
            'license_document' => 'required|file|mimes:pdf,jpg,png|max:5120',
            'id_document' => 'required|file|mimes:pdf,jpg,png|max:5120',
            'bank_document' => 'required|file|mimes:pdf,jpg,png|max:5120',
            'terms_accepted' => 'accepted',
        ]);

        try {
            // Upload Files
            $userId = auth()->id();
            $storagePath = "applications/{$userId}";
            
            $tradeDocPath = $request->file('trade_registry_document')->store($storagePath, 'local');
            $taxDocPath = $request->file('tax_document')->store($storagePath, 'local');
            $licenseDocPath = $request->file('license_document')->store($storagePath, 'local');
            $idDocPath = $request->file('id_document')->store($storagePath, 'local');
            $bankDocPath = $request->file('bank_document')->store($storagePath, 'local');

            // Create Application Record
            $application = BusinessApplication::create([
                'user_id' => $userId,
                'business_name' => $validated['business_name'],
                'category_id' => $validated['category_id'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'description' => $validated['description'],
                'trade_registry_no' => $validated['trade_registry_no'],
                'tax_id' => $validated['tax_id'],
                'trade_registry_document' => $tradeDocPath,
                'tax_document' => $taxDocPath,
                'license_document' => $licenseDocPath,
                'id_document' => $idDocPath,
                'bank_document' => $bankDocPath,
                'status' => 'pending',
                // tags handling if stored in json or related table? 
                // Currently BusinessApplication doesn't have tags relation, but we can disregard for Initial Application
                // Or we should store them in description or separate field if critical.
                // Assuming Admin will set tags upon approval.
            ]);

            // Notify the user about the submission
            auth()->user()->notify(new \App\Notifications\BusinessApplicationStatusNotification(
                'pending',
                $application->business_name
            ));

            return redirect()->route('business.application.status')->with('success', 'İşletme başvurunuz başarıyla alındı. Onay sürecinden sonra size bilgi verilecektir.');

        } catch (\Exception $e) {
            \Log::error('Application Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Başvuru sırasında bir hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    public function applicationStatus()
    {
        $application = BusinessApplication::with('category')
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        if (!$application) {
            return redirect()->route('business.apply');
        }

        return view('business.application_status', compact('application'));
    }

    public function editApplication()
    {
        $application = BusinessApplication::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'rejected'])
            ->latest()
            ->first();

        if (!$application) {
            return redirect()->route('business.application.status')->with('error', 'Düzenlenecek aktif bir başvuru bulunamadı.');
        }

        $categories = \App\Models\Category::all();
        return view('business.edit_application', compact('application', 'categories'));
    }

    public function updateApplication(Request $request)
    {
        $application = BusinessApplication::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'rejected'])
            ->latest()
            ->firstOrFail();

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'description' => 'required|string',
            'trade_registry_no' => 'required|string',
            'tax_id' => 'required|string',
            'trade_registry_document' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'tax_document' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'license_document' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'id_document' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'bank_document' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        try {
            $userId = auth()->id();
            $storagePath = "applications/{$userId}";
            $updateData = [
                'business_name' => $validated['business_name'],
                'category_id' => $validated['category_id'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'description' => $validated['description'],
                'trade_registry_no' => $validated['trade_registry_no'],
                'tax_id' => $validated['tax_id'],
                'status' => 'pending', // Reset to pending if it was rejected
            ];

            // Update files if provided
            $fileFields = ['trade_registry_document', 'tax_document', 'license_document', 'id_document', 'bank_document'];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    // Delete old file if exists
                    if ($application->$field) {
                        \Storage::disk('local')->delete($application->$field);
                        \Storage::disk('public')->delete($application->$field);
                    }
                    $updateData[$field] = $request->file($field)->store($storagePath, 'local');
                }
            }

            $application->update($updateData);

            // Notify the user about the update
            auth()->user()->notify(new \App\Notifications\BusinessApplicationStatusNotification(
                'pending',
                $application->business_name
            ));

            return redirect()->route('business.application.status')->with('success', 'Başvurunuz başarıyla güncellendi.');

        } catch (\Exception $e) {
            \Log::error('Application Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Güncelleme sırasında bir hata oluştu: ' . $e->getMessage())->withInput();
        }
    }
    public function getAvailableSlots(Request $request, Business $business)
    {
        $date = $request->input('date');

        if (!$date) {
            return response()->json(['error' => 'Tarih gerekli'], 422);
        }

        try {
            $carbonDate = \Carbon\Carbon::parse($date);
            // Translate dayOfWeek: Carbon 0=Sunday, 1=Monday. Match with DB.
            // As per BusinessHours creation, we likely use 0-6 or 1-7. Let's assume Carbon standard (0-6) for now.
            $dayOfWeek = $carbonDate->dayOfWeek; 

            // First check for a specific date override (e.g. holidays or special openings)


            $specialHours = \App\Models\BusinessHour::where('business_id', $business->id)
                ->where('special_date', $carbonDate->format('Y-m-d'))
                ->first();

            if ($specialHours) {
                $hours = $specialHours;

            } else {
                // Fallback to weekly schedule
                $hours = \App\Models\BusinessHour::where('business_id', $business->id)
                    ->where('day_of_week', $dayOfWeek) // 0=Sunday
                    ->whereNull('special_date') // Ensure we don't pick up a special date that coincidentally matches the day (unlikely due to unique constraint but safes)
                    ->first();

            }

            if (!$hours || $hours->is_closed) {

                return response()->json(['slots' => [], 'message' => 'İşletme bu tarihte kapalı.', 'is_closed' => true]);
            }

            // Calculate Base Price for the requested date/guests
            $guestCount = (int) $request->input('guest_count', 1);
            $totalBasePrice = $business->calculatePrice($date, $guestCount);

            $slots = [];
            // Robust parsing: Database might return "09:00:00" string or Carbon object
            $openTimeStr = $hours->open_time instanceof \Carbon\Carbon ? $hours->open_time->format('H:i') : \Carbon\Carbon::parse($hours->open_time)->format('H:i');
            $closeTimeStr = $hours->close_time instanceof \Carbon\Carbon ? $hours->close_time->format('H:i') : \Carbon\Carbon::parse($hours->close_time)->format('H:i');

            $start = \Carbon\Carbon::parse($date . ' ' . $openTimeStr);
            $end = \Carbon\Carbon::parse($date . ' ' . $closeTimeStr);
            
            // Handle cross-midnight hours if necessary, but assuming same-day for now.
            if ($end->lessThan($start)) {
                $end->addDay();
            }

            $current = $start->copy();
            $now = \Carbon\Carbon::now();

            $guestCount = $request->input('guest_count', 1);

            while ($current->lt($end)) {
                $slotStart = $current->copy();
                $slotEnd = $slotStart->copy()->addHours(2); // Assuming 2 hour duration for resource check
                
                // If date is today, filter past times
                $isFuture = true;
                if ($carbonDate->isToday()) {
                    if ($current->lte($now->copy()->addMinutes(15))) {
                        $isFuture = false;
                    }
                }

                if ($isFuture) {
                    // Accuracy: Check if at least ONE resource is free for this slot
                    $anyAvailable = \App\Models\Resource::where('business_id', $business->id)
                        ->where('is_available', true)
                        ->where('capacity', '>=', $guestCount)
                        ->whereDoesntHave('reservations', function($q) use ($slotStart, $slotEnd) {
                             $q->where('start_time', '<', $slotEnd)
                               ->where('end_time', '>', $slotStart)
                               ->whereIn('status', ['confirmed', 'pending', 'approved']);
                        })
                        ->exists();

                    if ($anyAvailable) {
                        $slots[] = $current->format('H:i');
                    }
                }
                $current->addHour();
            }

            return response()->json([
                'slots' => $slots, 
                'total_base_price' => $totalBasePrice,
                'is_closed' => false
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Saatler getirilemedi', 'details' => $e->getMessage()], 500);
        }
    }

    public function checkCoupon(Request $request)
    {
        $code = $request->input('code');
        $amount = (float)$request->input('amount', 0);

        if (!$code) {
           return response()->json(['valid' => false, 'message' => 'Kupon kodu boş olamaz.'], 422); 
        }

        $coupon = \App\Models\Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Geçersiz kupon kodu.'], 404);
        }

        if (!$coupon->is_active) {
            return response()->json(['valid' => false, 'message' => 'Bu kupon aktif değil.'], 400);
        }

        if ($coupon->expires_at && now()->gt($coupon->expires_at)) {
            return response()->json(['valid' => false, 'message' => 'Kupon süresi dolmuş.'], 400);
        }

        if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
            return response()->json(['valid' => false, 'message' => 'Kupon kullanım limiti dolmuş.'], 400);
        }

        if ($coupon->min_amount && $amount < $coupon->min_amount) {
            return response()->json(['valid' => false, 'message' => "Bu kuponu kullanmak için minimum sepet tutarı: {$coupon->min_amount} TL"], 400);
        }

        // Business specific check
        if ($coupon->business_id) {
            $requestBusinessId = $request->input('business_id');
            if (!$requestBusinessId || $coupon->business_id != $requestBusinessId) {
                 return response()->json(['valid' => false, 'message' => 'Bu kupon bu işletmede geçerli değildir.'], 400);
            }
        }

        // Calculate discount
        $discountAmount = 0;
        if ($coupon->type === 'percentage') {
            $discountAmount = ($amount * $coupon->value) / 100;
        } else {
            $discountAmount = $coupon->value;
        }

        // Ensure discount doesn't exceed total amount
        $discountAmount = min($discountAmount, $amount);

        return response()->json([
            'valid' => true,
            'discount_amount' => $discountAmount,
            'final_amount' => $amount - $discountAmount,
            'message' => 'Kupon uygulandı!',
            'coupon_code' => $coupon->code
        ]);
    }

    public function ogImage(Business $business)
    {
        $image = \App\Services\OGImageService::generateForBusiness($business);
        
        return response($image)->header('Content-Type', 'image/jpeg');
    }
}
