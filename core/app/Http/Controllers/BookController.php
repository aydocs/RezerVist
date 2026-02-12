<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\WalletTransaction;
use App\Models\User;

class BookController extends Controller
{
    // Show the checkout page
    public function index(Request $request, Business $business)
    {
        
        // Pass query params (date, guests) to the view
        $date = $request->query('date', now()->format('Y-m-d'));
        $guests = $request->query('guests', 2);
        $time = $request->query('time', '19:00');
        
        // Dynamic Pricing (with Happy Hour)
        $price = $business->calculatePrice($date, $guests, $time);

        // Fetch active staff for this business
        $staff = $business->staff()->where('is_active', true)->get();

        // Fetch locations
        $locations = $business->locations()->where('is_active', true)->get();

        // Check if user wants to use points
        $availablePoints = Auth::check() ? Auth::user()->points : 0;
        $pointsValue = $business->pointsToCurrency($availablePoints);

        return view('booking.checkout', compact('business', 'date', 'guests', 'time', 'price', 'staff', 'locations', 'availablePoints', 'pointsValue'));
    }

    // Process the reservation
    public function store(Request $request, Business $business)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'guests' => 'required|integer|min:1',
            'name' => 'required|string',
            'phone' => 'required|string',
            'note' => 'nullable|string',
            'payment_method' => 'required|in:wallet,card',
            'staff_id' => 'nullable|exists:staff,id',
            'location_id' => 'nullable|exists:locations,id',
            'coupon_code' => 'nullable|string',
        ]);
        
        // 1. Calculate Base Price (Dynamic with Happy Hour)
        $total_amount = $business->calculatePrice($validated['date'], $validated['guests'], $validated['time']);
        $discount_amount = 0;
        $loyalty_discount = 0;
        $points_spent = 0;
        $coupon_id = null;

        // 2. Handle Coupon
        if ($request->filled('coupon_code')) {
            $coupon = \App\Models\Coupon::where('code', $request->coupon_code)
                ->where('is_active', true)
                ->where(function($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->first();

            if ($coupon && $total_amount >= $coupon->min_amount) {
                if (!$coupon->max_uses || $coupon->used_count < $coupon->max_uses) {
                    $discount_amount = $coupon->calculateDiscount($total_amount);
                    $total_amount -= $discount_amount;
                    $coupon_id = $coupon->id;
                    $coupon->increment('used_count');
                }
            }
        }

        // 3. Handle Loyalty Points
        if ($request->boolean('use_points') && Auth::check()) {
            $user = Auth::user();
            if ($user->points > 0) {
                $points_spent = $user->points;
                $loyalty_discount = $business->pointsToCurrency($points_spent);
                
                // Cap discount to total amount
                if ($loyalty_discount > $total_amount) {
                    $loyalty_discount = $total_amount;
                    // Recalculate points needed for this exact discount if we want to be precise
                    $points_spent = $loyalty_discount * 10; 
                }
                
                $total_amount -= $loyalty_discount;
            }
        }

        // 3. Find Available Resource (Table/Space)
        $start_time = \Carbon\Carbon::parse($validated['date'] . ' ' . $validated['time']);
        $end_time = $start_time->copy()->addHour(); // Default 1 hour duration

        $resource = $business->resources()
            ->where('is_available', true)
            ->where('capacity', '>=', $validated['guests'])
            ->when($request->location_id, function($q) use ($request) {
                return $q->where('location_id', $request->location_id);
            })
            ->whereDoesntHave('reservations', function ($q) use ($start_time, $end_time) {
                $q->whereNotIn('status', ['cancelled', 'rejected'])
                  ->where(function ($query) use ($start_time, $end_time) {
                      $query->where('start_time', '<', $end_time)
                            ->where('end_time', '>', $start_time);
                  });
            })
            ->orderBy('capacity', 'asc')
            ->first();

        $user = Auth::user();

        // Handle Wallet Payment
        if ($validated['payment_method'] === 'wallet') {
            if ($user->balance < $total_amount) {
                return back()->with('error', 'Yetersiz bakiye. Lütfen cüzdanınıza bakiye yükleyin.');
            }

            return DB::transaction(function () use ($user, $total_amount, $id, $validated, $resource, $coupon_id, $discount_amount, $business) {
                // Deduct Balance
                $user->decrement('balance', $total_amount);

                // Create Reservation
                $start_time = \Carbon\Carbon::parse($validated['date'] . ' ' . $validated['time']);
                $reservation = Reservation::create([
                    'user_id' => $user->id,
                    'business_id' => $business->id,
                    'location_id' => $validated['location_id'] ?? null,
                    'resource_id' => $resource->id ?? null,
                    'staff_id' => $validated['staff_id'] ?? null,
                    'start_time' => $start_time,
                    'end_time' => $start_time->copy()->addHour(),
                    'status' => 'confirmed',
                    'note' => $validated['note'],
                    'price' => $total_amount + $discount_amount + $loyalty_discount,
                    'discount_amount' => $discount_amount,
                    'loyalty_discount' => $loyalty_discount,
                    'points_spent' => $points_spent,
                    'total_amount' => $total_amount,
                    'coupon_id' => $coupon_id,
                    'guest_count' => $validated['guests'],
                ]);

                // Deduct Points
                if ($points_spent > 0) {
                    $user->decrement('points', $points_spent);
                }

                // Record Transaction for User
                WalletTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $total_amount,
                    'type' => 'payment',
                    'status' => 'success',
                    'description' => $business->name . ' rezervasyonu için ödeme',
                    'reference_id' => $reservation->id,
                ]);

                // Credit Business Owner Wallet (Platform covers the cost of points/rewards essentially, or money just moves)
                // In this model, if 'wallet' money is real money deposited + rewards, 
                // we should move it to the Owner.
                $owner = $business->owner;
                if ($owner) {
                    $commissionRate = $business->commission_rate ?? 5; // 5% default
                    $commissionAmount = ($total_amount * $commissionRate) / 100;
                    $netEarning = $total_amount - $commissionAmount;

                    $owner->increment('balance', $netEarning);

                    WalletTransaction::create([
                        'user_id' => $owner->id,
                        'amount' => $netEarning,
                        'type' => 'earning',
                        'status' => 'success',
                        'description' => "Rezervasyon Geliri (#{$reservation->id}) - Cüzdan Ödemesi",
                        'reference_id' => $reservation->id,
                        'meta_data' => [
                            'gross' => $total_amount,
                            'commission' => $commissionAmount,
                            'paid_via' => 'user_wallet'
                        ]
                    ]);
                }

                return redirect()->route('booking.confirmation', ['id' => $reservation->id])
                    ->with('success', 'Rezervasyonunuz başarıyla tamamlandı!');
            });
        }

        // Handle Card Payment
        $start_time = \Carbon\Carbon::parse($validated['date'] . ' ' . $validated['time']);
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'business_id' => $business->id,
            'location_id' => $validated['location_id'] ?? null,
            'resource_id' => $resource->id ?? null,
            'staff_id' => $request->staff_id ?? null,
            'start_time' => $start_time,
            'end_time' => $start_time->copy()->addHour(),
            'status' => 'pending', // Card payment usually starts as pending until callback
            'note' => $validated['note'],
            'price' => $total_amount + $discount_amount + $loyalty_discount,
            'discount_amount' => $discount_amount,
            'loyalty_discount' => $loyalty_discount,
            'points_spent' => $points_spent,
            'total_amount' => $total_amount,
            'coupon_id' => $coupon_id,
            'guest_count' => $validated['guests'],
        ]);

        // Note: Points deduction for card payment should happen in callback/success logic
        // But for simplicity in this flow, we might need a way to "lock" points or just deduct on success.
        // For this task, I'll ensure the point calculation is passed/saved.

        return redirect()->route('booking.confirmation', ['id' => $reservation->id]);
    }

    public function confirmation(Request $request, $id)
    {
        $reservation = Reservation::with('business')->findOrFail($id);
        
        // Security: Allow if Owner OR if just completed payment (session has success) OR if valid token exists
        $isOwner = Auth::check() && $reservation->user_id == Auth::id();
        $hasSuccessFlash = session()->has('success');
        
        // Token Validation for Stateless Confirmation (Fixes Iyzico redirect session loss)
        $token = $request->query('token');
        $isValidToken = $token && hash_equals(hash_hmac('sha256', 'confirmation_' . $reservation->id, config('app.key')), $token);

        if (!$isOwner && !$hasSuccessFlash && !$isValidToken) {
             // If we are strict, 403. But for UX, if not logged in, redirect to login?
             if (!Auth::check()) {
                 return redirect()->route('login')->with('warning', 'Rezervasyonunuzu görüntülemek için giriş yapmalısınız.');
             }
             abort(403);
        }

        return view('booking.confirmation', compact('reservation'));
    }
}
