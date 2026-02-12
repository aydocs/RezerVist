<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\Resource;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    /**
     * Get authenticated user's business details for POS.
     */
    public function myBusiness(\Illuminate\Http\Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $business = $user->ownedBusiness;

        if (!$business) {
            return response()->json(['message' => 'No business found'], 404);
        }

        return response()->json($business);
    }

    /**
     * Show the business dashboard.
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'business' && $user->role !== 'admin') {
            return redirect('/');
        }
        
        $business = $user->ownedBusiness;
        if (!$business) {
             return redirect()->route('vendor.business.edit');
        }

        $locationId = $request->get('location_id');
        $locations = $business->locations;

        // 1. Core KPIs
        $query = Reservation::where('business_id', $business->id)->where('status', '!=', 'pending_payment');
        
        if ($locationId) {
            if ($locationId === 'main') $query->whereNull('location_id');
            else $query->where('location_id', $locationId);
        }
        
        $reservations = $query->get();
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        $revenueQuery = Reservation::where('business_id', $business->id)->whereIn('status', ['completed', 'approved']);
        if ($locationId) {
            if ($locationId === 'main') $revenueQuery->whereNull('location_id');
            else $revenueQuery->where('location_id', $locationId);
        }

        // Use transaction date (created_at) for revenue KPIs
        $revenueThisMonth = (clone $revenueQuery)->where('created_at', '>=', $thisMonth)->sum('total_amount');
        $revenueLastMonth = (clone $revenueQuery)->whereBetween('created_at', [$lastMonth, $lastMonthEnd])->sum('total_amount');

        $revenueTrend = $revenueLastMonth > 0 ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 : 0;

        // Calculate Returning Customer Rate
        $totalCustomers = Reservation::where('business_id', $business->id)
            ->distinct('user_id')
            ->count('user_id');
        
        $returningCustomers = Reservation::where('business_id', $business->id)
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(id) > 1')
            ->get()
            ->count();
        
        $repeatRate = $totalCustomers > 0 ? ($returningCustomers / $totalCustomers) * 100 : 0;

        // Staff Performance Leaderboard
        $staffStats = $business->staff()
            ->withCount(['reservations as total_bookings'])
            ->orderBy('rating', 'desc')
            ->take(5)
            ->get();

        $stats = [
            'revenue' => $reservations->whereIn('status', ['completed', 'approved'])->sum('total_amount'),
            'revenue_this_month' => $revenueThisMonth,
            'revenue_trend' => round($revenueTrend, 1),
            'visitors' => $reservations->whereIn('status', ['completed', 'approved'])->sum('guest_count'), 
            'pending_reservations' => $reservations->where('status', 'pending')->count(),
            'approved_reservations' => $reservations->where('status', 'approved')->count(),
            'total_reservations' => $reservations->count(),
            'today_reservations' => $reservations->whereBetween('start_time', [now()->startOfDay(), now()->endOfDay()])->count(),
            'avg_rating' => $business->rating,
            'active_coupons' => $business->coupons()->where('is_active', true)->count(),
            'repeat_customer_rate' => round($repeatRate, 1),
            'staff_stats' => $staffStats,
        ];

        // 2. Revenue Chart Logic (Transaction based)
        $period = $request->get('period', 'monthly');
        $labels = [];
        $seriesGross = [];
        $seriesNet = [];
        $commissionRate = $business->commission_rate ?? 0;

        if ($period === 'weekly') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->locale('tr')->dayName;
                $val = (clone $revenueQuery)->whereDate('created_at', $date->format('Y-m-d'))->sum('total_amount');
                $seriesGross[] = (float)$val;
                $seriesNet[] = (float)($val - ($val * $commissionRate / 100));
            }
        } elseif ($period === 'monthly') {
            $daysInMonth = now()->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $labels[] = $i;
                $val = (clone $revenueQuery)->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->whereDay('created_at', $i)
                    ->sum('total_amount');
                $seriesGross[] = (float)$val;
                $seriesNet[] = (float)($val - ($val * $commissionRate / 100));
            }
        } else {
            $labels = ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];
            for ($i = 1; $i <= 12; $i++) {
                $val = (clone $revenueQuery)->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', $i)
                    ->sum('total_amount');
                $seriesGross[] = (float)$val;
                $seriesNet[] = (float)($val - ($val * $commissionRate / 100));
            }
        }

        $chartSeries = [
            ['name' => 'Brüt Gelir', 'data' => $seriesGross],
            ['name' => 'Net Kazanç', 'data' => $seriesNet],
        ];

        // 3. Top Services / Menus
        $topMenus = \DB::table('menu_reservation')
            ->join('reservations', 'menu_reservation.reservation_id', '=', 'reservations.id')
            ->join('menus', 'menu_reservation.menu_id', '=', 'menus.id')
            ->where('reservations.business_id', $business->id)
            ->whereIn('reservations.status', ['completed', 'approved'])
            ->when($locationId, function($q) use ($locationId) {
                if ($locationId === 'main') return $q->whereNull('reservations.location_id');
                return $q->where('reservations.location_id', $locationId);
            })
            ->select('menus.name', \DB::raw('count(*) as count'), \DB::raw('sum(menus.price) as total_revenue'))
            ->groupBy('menus.id', 'menus.name')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        // 4. Occupancy Rate
        $occupancyLabels = [];
        $occupancyData = [];
        $capacity = $business->capacity ?: 100;

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $occupancyLabels[] = $date->format('d/m');
            $guests = (clone $query)->where('status', '!=', 'cancelled')
                ->whereDate('start_time', $date)
                ->sum('guest_count');
            $occupancyData[] = $capacity > 0 ? round(($guests / $capacity) * 100, 1) : 0;
        }

        $recentReservations = (clone $query)->with(['user', 'menus', 'location'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // 5. Dynamic Onboarding Progress Calculation
        $onboardingSteps = [
            'profile' => !empty($business->description) && !empty($business->address),
            'menu' => $business->menus()->count() > 0,
            'hours' => $business->hours()->count() > 0,
            'staff' => $business->staff()->count() > 0,
            'first_sale' => $reservations->whereIn('status', ['completed', 'approved'])->count() > 0,
        ];

        $completedSteps = count(array_filter($onboardingSteps));
        $totalSteps = count($onboardingSteps);
        $onboardingPercent = round(($completedSteps / $totalSteps) * 100);

        $onboarding = [
            'percent' => $onboardingPercent,
            'steps' => $onboardingSteps
        ];

        // 6. Recent Reviews
        $recentReviews = $business->reviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'stats', 
            'recentReservations', 
            'business', 
            'chartSeries', 
            'labels', 
            'period', 
            'topMenus',
            'occupancyLabels',
            'occupancyData',
            'locations',
            'onboarding',
            'recentReviews'
        ));
    }

    /**
     * Show the business management page.
     */
    public function editBusiness()
    {
        $user = Auth::user();
        $business = $user->ownedBusiness; // Assuming hasOne relationship

        if (!$business) {
            return redirect()->route('home')->with('error', 'İşletme kaydınız bulunamadı.');
        }

        return view('vendor.business.edit', compact('business'));
    }

    /**
     * Update business details.
     */
    public function updateBusiness(Request $request)
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;

        if (!$business) {
            return abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'description' => 'required|string|min:20',
            'address' => 'required|string',
            'min_reservation_amount' => 'nullable|numeric|min:0',
            'category' => 'required|string',
            'tags' => 'nullable|array', // "Features" are tags in UI but we store as simple JSON array or existing logic
            'tags.*' => 'string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'waiter_kiosk_enabled' => 'nullable|boolean',
            'surge_threshold' => 'nullable|integer|min:0|max:100',
            'surge_multiplier' => 'nullable|numeric|min:1.0|max:5.0',
            'reservation_start_time' => 'nullable|date_format:H:i',
            'reservation_end_time' => 'nullable|date_format:H:i',
            'reservation_slot_duration' => 'nullable|integer|in:30,60,90,120',
        ]);

        // Prepare reservation time slots JSON
        $timeSlots = null;
        if ($request->has(['reservation_start_time', 'reservation_end_time'])) {
            $timeSlots = [[
                'start' => $validated['reservation_start_time'] ?? '10:00',
                'end' => $validated['reservation_end_time'] ?? '23:00',
                'slot_duration' => (int) ($validated['reservation_slot_duration'] ?? 60)
            ]];
        }

        $business->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'description' => $validated['description'],
            'address' => $validated['address'],
            'min_reservation_amount' => $validated['min_reservation_amount'] ?? 0,
            'category' => $validated['category'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'features' => isset($validated['tags']) ? json_encode($validated['tags']) : null,
            'waiter_kiosk_enabled' => $request->has('waiter_kiosk_enabled'),
            'surge_threshold' => $validated['surge_threshold'] ?? 80,
            'surge_multiplier' => $validated['surge_multiplier'] ?? 1.5,
            'reservation_time_slots' => $timeSlots ?? $business->reservation_time_slots,
        ]);

        return back()->with('success', 'İşletme bilgileri güncellendi.');
    }

    /**
     * Reset POS Device pairing for the business.
     */
    public function resetPosDevice()
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'İşletme hesabı bulunamadı.'
            ], 404);
        }

        $business->update([
            'device_fingerprint' => null,
            'device_registered_at' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'POS cihaz eşleşmesi başarıyla sıfırlandı. Yeni bir cihazdan giriş yapabilirsiniz.'
        ]);
    }


    /**
     * Show vendor reservations.
     */
    public function reservations(Request $request)
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;
        
        if (!$business) {
             return redirect()->route('home');
        }

        $locations = $business->locations;

        $reservations = Reservation::where('business_id', $business->id)
            ->where('status', '!=', 'pending_payment')
            ->when($request->location_id, function($q) use ($request) {
                if ($request->location_id === 'main') {
                    return $q->whereNull('location_id');
                }
                return $q->where('location_id', $request->location_id);
            })
            ->when($request->reservation_id, function($q) use ($request) {
                return $q->where('id', $request->reservation_id);
            })
            ->when($request->status, function($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->with(['user', 'staff', 'location'])
            ->when($request->sort_by === 'oldest', function($q) {
                return $q->orderBy('updated_at', 'asc');
            }, function($q) {
                return $q->orderBy('updated_at', 'desc');
            })
            ->paginate(10)
            ->withQueryString();

        $staff = $business->staff()->where('is_active', true)->get();

        return view('vendor.reservations.index', compact('reservations', 'staff', 'locations'));
    }

    public function updateReservation(Request $request, $id)
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;

        if (!$business) {
            return abort(403);
        }

        $reservation = Reservation::where('business_id', $business->id)->findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,cancelled',
            'staff_id' => 'nullable|exists:staff,id',
        ]);

        if ($validated['status'] === 'cancelled') {
            // Can only cancel Approved reservations
            if ($reservation->status !== 'approved') {
                 return back()->with('error', 'Sadece onaylanmış rezervasyonlar iptal edilebilir.');
            }

            // 24 Hour Rule
            if (now()->diffInHours($reservation->start_time, false) < 24) {
                return back()->with('error', 'Rezervasyona 24 saatten az kaldığı için iptal edilemez.');
            }

            // Refund Logic: Add price to user's balance
            $reservation->user->increment('balance', $reservation->price);
        }

        $updateData = ['status' => $validated['status']];
        if (isset($validated['staff_id'])) {
            $updateData['staff_id'] = $validated['staff_id'];
        }

        $reservation->update($updateData);

        // Check if staff was assigned
        $staffWasAssigned = isset($validated['staff_id']) && $reservation->wasChanged('staff_id') && $validated['staff_id'];

        // Send Staff Assignment Notification (if staff was assigned)
        if ($staffWasAssigned) {
            $reservation->load('staff');
            if ($reservation->staff) {
                $reservation->user->notify(new \App\Notifications\StaffAssignedNotification($reservation, $reservation->staff));
            }
        }

        // Send Status Notification (only if staff was NOT assigned, to avoid duplicate notifications)
        if (!$staffWasAssigned) {
            $reservation->user->notify(new \App\Notifications\ReservationStatusNotification($reservation, $validated['status'], 'business'));
        }

        $message = 'Rezervasyon durumu güncellendi.';
        if ($validated['status'] === 'cancelled' && isset($reservation->price) && $reservation->price > 0) {
            $message .= ' Tutar müşterinin bakiyesine iade edildi.';
        }

        return back()->with('success', $message);
    }

    /**
     * Show business hours management page
     */
    public function editHours()
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;

        if (!$business) {
            return redirect()->route('home')->with('error', 'İşletme kaydınız bulunamadı.');
        }

        $hours = $business->hours()->whereNull('special_date')->get();

        return view('vendor.business.hours', compact('business', 'hours'));
    }

    /**
     * Update business hours
     */
    public function updateHours(Request $request)
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;

        if (!$business) {
            return redirect()->route('home')->with('error', 'İşletme kaydınız bulunamadı.');
        }

        // Delete existing regular hours
        $business->hours()->whereNull('special_date')->delete();

        // Create new hours
        foreach ($request->hours as $dayNum => $data) {
            if (isset($data['is_closed']) && $data['is_closed']) {
                // Create closed day entry
                $business->hours()->create([
                    'day_of_week' => $dayNum,
                    'is_closed' => true,
                    'open_time' => null,
                    'close_time' => null,
                ]);
            } else {
                // Create regular hours
                $business->hours()->create([
                    'day_of_week' => $dayNum,
                    'is_closed' => false,
                    'open_time' => $data['open_time'] ?? '09:00',
                    'close_time' => $data['close_time'] ?? '18:00',
                    'discount_percent' => $data['discount_percent'] ?? null,
                ]);
            }
        }

        return redirect()->route('vendor.business.hours.edit')
            ->with('success', 'Çalışma saatleri başarıyla güncellendi.');
    }
    /**
     * Store a new business image.
     */
    public function storeImage(Request $request)
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;

        if (!$business) {
            return abort(404);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // Increased to 5MB
        ]);

        if ($request->hasFile('image')) {
            // Use FileUploadService for secure and optimized upload
            $paths = \App\Services\FileUploadService::uploadImage($request->file('image'), 'business_images', true);
            
            $business->images()->create([
                'image_path' => $paths['original'],
                'thumbnail_path' => $paths['thumbnail'],
            ]);

            return back()->with('success', 'Fotoğraf başarıyla yüklendi ve optimize edildi.');
        }

        return back()->with('error', 'Dosya yüklenirken bir hata oluştu.');
    }

    /**
     * Delete a business image.
     */
    public function deleteImage($id)
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;

        if (!$business) {
            return abort(404);
        }

        $image = $business->images()->where('id', $id)->firstOrFail();

        // Use FileUploadService to delete (handles main image and thumbnail)
        \App\Services\FileUploadService::delete($image->image_path);

        $image->delete();

        return back()->with('success', 'Fotoğraf silindi.');
    }
    
    public function customers()
    {
        $business = Auth::user()->ownedBusiness;
        if (!$business) return abort(403);

        $customers = Reservation::where('business_id', $business->id)
            ->with('user')
            ->select('user_id', \DB::raw('count(*) as total_reservations'), \DB::raw('sum(price) as total_spent'), \DB::raw('max(start_time) as last_reservation'))
            ->groupBy('user_id')
            ->orderBy('total_spent', 'desc')
            ->paginate(15);

        return view('vendor.customers.index', compact('customers'));
    }

    public function reviews()
    {
        $business = Auth::user()->ownedBusiness;
        if (!$business) return abort(403);

        $reviews = $business->reviews()
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('vendor.reviews.index', compact('reviews'));
    }

    public function setup()
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;
        if (!$business) return redirect()->route('vendor.business.edit');

        // Dynamic Onboarding Progress Calculation
        $reservations = Reservation::where('business_id', $business->id)->get();
        $onboardingSteps = [
            'profile' => !empty($business->description) && !empty($business->address),
            'menu' => $business->menus()->count() > 0,
            'hours' => $business->hours()->count() > 0,
            'staff' => $business->staff()->count() > 0,
            'first_sale' => $reservations->whereIn('status', ['completed', 'approved'])->count() > 0,
        ];

        $completedSteps = count(array_filter($onboardingSteps));
        $totalSteps = count($onboardingSteps);
        $onboardingPercent = round(($completedSteps / $totalSteps) * 100);

        $onboarding = [
            'percent' => $onboardingPercent,
            'steps' => $onboardingSteps
        ];

        return view('vendor.setup.index', compact('business', 'onboarding'));
    }

    public function finance(Request $request)
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;
        if (!$business) return abort(403);

        $period = $request->get('period', 'monthly'); // Default to monthly
        $locationId = $request->get('location_id');
        $locations = $business->locations;

        // Use transaction date (created_at) for finance as requested
        $query = Reservation::where('business_id', $business->id)->whereIn('status', ['completed', 'approved']);
        
        if ($locationId) {
            if ($locationId === 'main') $query->whereNull('location_id');
            else $query->where('location_id', $locationId);
        }

        $labels = [];
        $seriesGross = [];
        $seriesComm = [];
        $seriesNet = [];
        $commissionRate = $business->commission_rate ?? 1;

        if ($period === 'weekly') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->locale('tr')->dayName;
                $val = (clone $query)->whereDate('created_at', $date->format('Y-m-d'))->sum('total_amount');
                $seriesGross[] = (float)$val;
                $seriesComm[] = (float)($val * $commissionRate / 100);
                $seriesNet[] = (float)($val - ($val * $commissionRate / 100));
            }
        } elseif ($period === 'monthly') {
            $daysInMonth = now()->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $labels[] = $i;
                $val = (clone $query)->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->whereDay('created_at', $i)
                    ->sum('total_amount');
                $seriesGross[] = (float)$val;
                $seriesComm[] = (float)($val * $commissionRate / 100);
                $seriesNet[] = (float)($val - ($val * $commissionRate / 100));
            }
        } else {
            // Yearly/All
            $labels = ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];
            for ($i = 1; $i <= 12; $i++) {
                $val = (clone $query)->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', $i)
                    ->sum('total_amount');
                $seriesGross[] = (float)$val;
                $seriesComm[] = (float)($val * $commissionRate / 100);
                $seriesNet[] = (float)($val - ($val * $commissionRate / 100));
            }
        }

        $totalRevenue = array_sum($seriesGross);
        $totalNet = array_sum($seriesNet);
        $totalCommission = array_sum($seriesComm);
        
        $reservations = (clone $query)->get();
        $reservationCount = $reservations->count();
        $iyzicoEstimate = ($totalRevenue * 0.011) + ($reservationCount * 0.25);

        // Daily Distribution
        $dailyDistribution = [
            'labels' => ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'],
            'data' => array_fill(0, 7, 0)
        ];
        
        $reservationsPerDay = (clone $query)->selectRaw('WEEKDAY(start_time) as day, count(*) as count')
            ->groupBy('day')
            ->get();
            
        foreach ($reservationsPerDay as $res) {
            $dailyDistribution['data'][$res->day] = $res->count;
        }

        $withdrawals = \App\Models\Withdrawal::where('user_id', Auth::id())->latest()->take(10)->get();
        $walletTransactions = \App\Models\WalletTransaction::where('user_id', Auth::id())->latest()->take(20)->get();

        return view('vendor.finance.index', compact(
            'business', 'locations', 'period', 'labels', 'seriesGross', 'seriesComm', 'seriesNet', 
            'totalRevenue', 'totalNet', 'totalCommission', 'iyzicoEstimate',
            'dailyDistribution', 'reservations', 'withdrawals', 'walletTransactions'
        ));
    }

    public function exportFinance(Request $request)
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;
        if (!$business) return abort(403);

        $period = $request->get('period', 'monthly');
        $locationId = $request->get('location_id');

        // Query basically similar to finance index for consistency
        $query = Reservation::where('business_id', $business->id)->whereIn('status', ['completed', 'approved']);
        
        if ($locationId) {
            if ($locationId === 'main') $query->whereNull('location_id');
            else $query->where('location_id', $locationId);
        }

        // Apply filters (defaults to 'all' if not specified for export, or we can use the same period logic)
        // Let's assume export is "All Time" or respects current period.
        // For simplicity and "Download All Transactions" text sake, let's grab everything unless filtered.
        
        // Actually, the button says "Tüm İşlemleri İndir", implies ALl.
        // But respecting filters is better UX.
        if ($period === 'weekly') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($period === 'monthly') {
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } elseif ($period === '1y') {
            $query->whereYear('created_at', now()->year);
        }
        
        $reservations = (clone $query)->orderBy('created_at', 'desc')->get();
        
        $totalRevenue = $reservations->sum('total_amount');
        $commissionRate = $business->commission_rate ?? 1;
        $totalCommission = $totalRevenue * ($commissionRate / 100);
        $totalNet = $totalRevenue - $totalCommission;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('vendor.finance.pdf', compact('business', 'reservations', 'totalRevenue', 'totalCommission', 'totalNet'));
        
        return $pdf->download('finansal-rapor-' . now()->format('d-m-Y') . '.pdf');
    }

    public function registerIyzicoSubMerchant(Request $request)
    {
        $business = Auth::user()->ownedBusiness;
        if (!$business) return back()->with('error', 'İşletme bulunamadı.');

        $validated = $request->validate([
            'submerchant_type' => 'required|in:PERSONAL,PRIVATE_COMPANY,LIMITED_OR_JOINT_STOCK_COMPANY',
            'legal_company_name' => 'required|string|max:255',
            'tax_office' => 'required_if:submerchant_type,PRIVATE_COMPANY,LIMITED_OR_JOINT_STOCK_COMPANY',
            'tax_number' => 'required_if:submerchant_type,PRIVATE_COMPANY,LIMITED_OR_JOINT_STOCK_COMPANY',
            'identity_number' => 'required_if:submerchant_type,PERSONAL',
            'iyzico_iban' => 'required|string|size:34', // Updated to handle spaces from auto-formatter
        ]);

        // Clean spaces from IBAN
        $validated['iyzico_iban'] = str_replace(' ', '', $validated['iyzico_iban']);

        // For Iyzico, Identity Number (TCKN) is passed as tax_number for PERSONAL submerchants
        if ($validated['submerchant_type'] === 'PERSONAL') {
            $validated['tax_number'] = $validated['identity_number'];
        }

        $service = new \App\Services\IyzicoMarketplaceService();
        $result = $service->registerSubMerchant($business, $validated);

        if ($result['status'] === 'success') {
            return back()->with('success', 'Iyzico Pazaryeri kaydınız başarıyla tamamlandı. Artık hakedişleriniz otomatik olarak banka hesabınıza yatacaktır.');
        }

        return back()->with('error', 'Iyzico hatası: ' . $result['message']);
    }

    public function occupancy(Request $request)
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;
        if (!$business) return abort(403);

        $locationId = $request->get('location_id');
        $locations = $business->locations;

        $query = Reservation::where('business_id', $business->id)
            ->whereIn('status', ['completed', 'approved']);
        
        if ($locationId) {
            if ($locationId === 'main') $query->whereNull('location_id');
            else $query->where('location_id', $locationId);
        }

        // 1. Hourly Distribution (00:00 - 23:00)
        $hourlyData = array_fill(0, 24, 0);
        $resPerHour = (clone $query)->selectRaw('HOUR(start_time) as hour, count(*) as count')
            ->groupBy('hour')
            ->get();
        foreach ($resPerHour as $res) {
            $hourlyData[$res->hour] = $res->count;
        }

        // 2. Daily Distribution
        $dailyData = array_fill(0, 7, 0);
        $resPerDay = (clone $query)->selectRaw('WEEKDAY(start_time) as day, count(*) as count')
            ->groupBy('day')
            ->get();
        foreach ($resPerDay as $res) {
            $dailyData[$res->day] = $res->count;
        }

        // 3. Resource Occupancy
        $resourceStats = (clone $query)->selectRaw('resource_id, count(*) as count')
            ->with('resource:id,name')
            ->groupBy('resource_id')
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->resource ? $item->resource->name : 'Genel',
                    'count' => $item->count
                ];
            });

        return view('vendor.occupancy.index', compact(
            'business', 'locations', 'hourlyData', 'dailyData', 'resourceStats'
        ));
    }

    public function getAnalyticsData(Request $request)
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;
        if (!$business) return response()->json(['error' => 'Unauthorized'], 403);

        $type = $request->get('type', 'finance');
        $period = $request->get('period', '7d');
        $locationId = $request->get('location_id');

        // Base query - Finance uses transaction date, Occupancy uses reservation date
        $query = Reservation::where('business_id', $business->id)
            ->whereIn('status', ['completed', 'approved']);

        if ($locationId) {
            if ($locationId === 'main') $query->whereNull('location_id');
            else $query->where('location_id', $locationId);
        }

        $dateColumn = ($type === 'finance') ? 'created_at' : 'start_time';

        // Filter by period
        if ($period === '1d') {
            $query->whereDate($dateColumn, now());
        } elseif ($period === '7d') {
            $query->where($dateColumn, '>=', now()->subDays(7));
        } elseif ($period === '1m') {
            $query->where($dateColumn, '>=', now()->subMonth());
        } elseif ($period === '1y') {
            $query->where($dateColumn, '>=', now()->subYear());
        }

        if ($type === 'finance') {
            $labels = [];
            $seriesGross = [];
            $seriesNet = [];
            $seriesComm = [];
            
            $commissionRate = $business->commission_rate ?? 0;

            if ($period === '1d') {
                for ($i = 0; $i < 24; $i++) {
                    $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                    $val = (clone $query)->whereRaw("HOUR($dateColumn) = ?", [$i])->sum('total_amount');
                    $seriesGross[] = (float)$val;
                    $seriesComm[] = (float)($val * $commissionRate / 100);
                    $seriesNet[] = (float)($val - ($val * $commissionRate / 100));
                }
            } elseif ($period === '7d' || $period === '1m') {
                $days = $period === '7d' ? 7 : 30;
                for ($i = $days - 1; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $labels[] = $date->format('d M');
                    $val = (clone $query)->whereDate($dateColumn, $date->format('Y-m-d'))->sum('total_amount');
                    $seriesGross[] = (float)$val;
                    $seriesComm[] = (float)($val * $commissionRate / 100);
                    $seriesNet[] = (float)($val - ($val * $commissionRate / 100));
                }
            } else {
                for ($i = 11; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $labels[] = $date->format('M Y');
                    $val = (clone $query)->whereMonth($dateColumn, $date->month)
                        ->whereYear($dateColumn, $date->year)
                        ->sum('total_amount');
                    $seriesGross[] = (float)$val;
                    $seriesComm[] = (float)($val * $commissionRate / 100);
                    $seriesNet[] = (float)($val - ($val * $commissionRate / 100));
                }
            }

            $totalGross = array_sum($seriesGross);
            $totalComm = array_sum($seriesComm);
            $totalNet = array_sum($seriesNet);
            $count = (clone $query)->count();
            $aov = $count > 0 ? $totalGross / $count : 0;

            return response()->json([
                'labels' => $labels,
                'series' => [
                    ['name' => 'Brüt Gelir', 'data' => $seriesGross],
                    ['name' => 'Komisyon', 'data' => $seriesComm],
                    ['name' => 'Net Kazanç', 'data' => $seriesNet],
                ],
                'stats' => [
                    'total_revenue' => number_format($totalGross, 2, ',', '.') . ' ₺',
                    'commission' => number_format($totalComm, 2, ',', '.') . ' ₺',
                    'net_earnings' => number_format($totalNet, 2, ',', '.') . ' ₺',
                    'aov' => number_format($aov, 2, ',', '.') . ' ₺',
                    'transaction_count' => $count,
                    'growth' => '+12.5%' // Mock value for visual polish
                ]
            ]);
        } else {
            // Occupancy
            $hourlyData = array_fill(0, 24, 0);
            $resPerHour = (clone $query)->selectRaw('HOUR(start_time) as hour, count(*) as count')
                ->groupBy('hour')
                ->get();
            foreach ($resPerHour as $res) $hourlyData[$res->hour] = $res->count;

            $dailyData = array_fill(0, 7, 0);
            $resPerDay = (clone $query)->selectRaw('WEEKDAY(start_time) as day, count(*) as count')
                ->groupBy('day')
                ->get();
            foreach ($resPerDay as $res) $dailyData[$res->day] = $res->count;

            return response()->json([
                'hourly' => $hourlyData,
                'daily' => $dailyData,
                'max_hour' => array_search(max($hourlyData), $hourlyData) . ':00',
                'max_day' => ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'][array_search(max($dailyData), $dailyData)]
            ]);
        }
    }

    /**
     * Seeds demo data for POS system (Categories, Products, Tables, Staff).
     */
    public function seedDemoData()
    {
        $business = Auth::user()->ownedBusiness;
        if (!$business) return response()->json(['success' => false, 'message' => 'İşletme bulunamadı.']);

        // 1. Create Staff
        if ($business->staff()->count() === 0) {
            $business->staff()->create([
                'name' => 'Demo Garson',
                'position' => 'Garson',
                'pin_code' => '1234',
                'is_active' => true,
                'permissions' => ['take_orders', 'view_tables']
            ]);
        }

        // 2. Create Resources (Tables)
        if ($business->resources()->count() === 0) {
            $tables = [
                ['name' => 'Salon Masa 1', 'capacity' => 4, 'category' => 'SALON'],
                ['name' => 'Salon Masa 2', 'capacity' => 2, 'category' => 'SALON'],
                ['name' => 'Bahçe Masa 1', 'capacity' => 6, 'category' => 'BAHÇE'],
                ['name' => 'Bahçe Masa 2', 'capacity' => 4, 'category' => 'BAHÇE'],
            ];
            foreach ($tables as $t) {
                $business->resources()->create($t);
            }
        }

        // 3. Create Menu Items
        if ($business->menus()->count() === 0) {
            $items = [
                ['name' => 'Margherita Pizza', 'price' => 280, 'category' => 'PİZZALAR', 'description' => 'Taze fesleğen ve mozzarella'],
                ['name' => 'Classic Burger', 'price' => 320, 'category' => 'BURGERLER', 'description' => '180g dana köfte, cheddar'],
                ['name' => 'Fettuccine Alfredo', 'price' => 260, 'category' => 'MAKARNALAR', 'description' => 'Kremalı mantar sosuyla'],
                ['name' => 'Çoban Salata', 'price' => 140, 'category' => 'SALATALAR', 'description' => 'Taze sebzelerle'],
                ['name' => 'Coca Cola', 'price' => 65, 'category' => 'İÇECEKLER', 'description' => '330ml'],
                ['name' => 'Su', 'price' => 25, 'category' => 'İÇECEKLER', 'description' => '500ml'],
            ];
            foreach ($items as $item) {
                $business->menus()->create($item + ['is_available' => true]);
            }
        }

        // 4. Set Master PIN if not set
        if (empty($business->master_pin)) {
            $business->update(['master_pin' => '00000000']);
        }

        return response()->json([
            'success' => true, 
            'message' => 'Demo verileri başarıyla yüklendi! Artık POS uygulamasını test edebilirsiniz.'
        ]);
    }

    /**
     * Clears all business data (Staff, Resources, Menus) for a fresh start.
     */
    public function clearDemoData()
    {
        $business = Auth::user()->ownedBusiness;
        if (!$business) return response()->json(['success' => false, 'message' => 'İşletme bulunamadı.']);

        // Delete all related data
        $business->menus()->delete();
        $business->resources()->delete();
        $business->staff()->delete();

        return response()->json([
            'success' => true, 
            'message' => 'Tüm veriler (Menü, Masa, Personel) başarıyla silindi. Artık sıfırdan başlayabilirsiniz.'
        ]);
    }
}
