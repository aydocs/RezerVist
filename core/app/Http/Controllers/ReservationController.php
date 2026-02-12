<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Reservation;
use App\Services\SmsService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        // Get reservations for the logged-in user
        $reservations = $request->user()->reservations()
            ->where('status', '!=', 'pending_payment')
            ->with(['business', 'resource'])
            ->orderBy('start_time', 'desc')
            ->get();

        return response()->json($reservations);
    }

    public function store(\App\Http\Requests\StoreReservationRequest $request)
    {
        $user = $request->user();

        // Get validated data from FormRequest
        $validated = $request->validated();

        // Merge date and time if provided separately (handled by FormRequest already)
        if (isset($validated['reservation_date']) && isset($validated['reservation_time'])) {
            $start = \Carbon\Carbon::parse($validated['reservation_date'].' '.$validated['reservation_time']);
            $validated['start_time'] = $start->toDateTimeString();
            $validated['end_time'] = $start->copy()->addHours(2)->toDateTimeString();
        }
        // Add default end_time if start_time is present but end_time is not
        elseif (isset($validated['start_time']) && ! isset($validated['end_time'])) {
            $start = \Carbon\Carbon::parse($validated['start_time']);
            $validated['end_time'] = $start->copy()->addHours(2)->toDateTimeString();
        }

        // Search for a resource that is both suitable and free during this time
        $startTime = $validated['start_time'];
        $endTime = $validated['end_time'];
        $locationId = $validated['location_id'] ?? null;
        $resourceId = $request->input('resource_id'); // Respect user choice if provided

        $resourceQuery = \App\Models\Resource::where('business_id', $validated['business_id'])
            ->where('is_available', true)
            ->where('capacity', '>=', $validated['guest_count'])
            ->when($locationId, function ($q) use ($locationId) {
                return $q->where('location_id', $locationId);
            })
            ->when($resourceId, function ($q) use ($resourceId) {
                return $q->where('id', $resourceId);
            })
            ->whereDoesntHave('reservations', function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime)
                    ->whereIn('status', ['confirmed', 'pending', 'approved']);
            })
            ->orderBy('capacity', 'asc');

        $availableResource = $resourceQuery->first();

        if (! $availableResource) {
            return response()->json(['message' => 'Seçilen zaman aralığı doludur veya uygun masa bulunamadı.'], 409);
        }

        $resourceId = $availableResource->id;

        // Calculate Total Price (Base Price)
        $business = \App\Models\Business::find($validated['business_id']);
        $totalPrice = $business->calculatePrice($validated['reservation_date'], $validated['guest_count'], $validated['reservation_time']);
        $selectedServices = [];

        $servicesInput = $validated['services'] ?? [];
        if (! empty($servicesInput)) {
            $serviceIds = collect($servicesInput)->pluck('id')->toArray();
            $serviceData = collect($servicesInput)->keyBy('id');

            $services = \App\Models\Menu::whereIn('id', $serviceIds)->get();

            foreach ($services as $service) {
                $quantity = $serviceData->get($service->id)['quantity'] ?? 1;
                // Business defined price is used (Zero Commission)
                $totalPrice += ($service->price * $quantity);
                // Store with quantity for attachment later
                $selectedServices[] = [
                    'model' => $service,
                    'quantity' => $quantity,
                    'price' => $service->price,
                ];
            }
        }

        // Round up to match frontend display
        $totalPrice = ceil($totalPrice);

        // --- PHASE 3: Coupon Application ---
        $discountAmount = 0;
        $couponId = null;
        if ($request->has('coupon_code')) {
            $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();
            if ($coupon && $coupon->isValid($totalPrice)) {
                $discountAmount = $coupon->calculateDiscount($totalPrice);
                $totalPrice = max(0, $totalPrice - $discountAmount);
                $couponId = $coupon->id;

                // Mark coupon as used
                $coupon->increment('used_count');
            }
        }

        // Determine initial status based on payment method and guest count
        // Default success status is 'approved' in this system
        $status = $validated['payment_method'] === 'wallet' ? 'approved' : 'pending_payment';

        // --- PHASE 2: Group Booking Logic ---
        // If guest count > 8, it always needs business approval (override 'confirmed'/'approved')
        if ($validated['guest_count'] > 8) {
            $status = 'pending'; // Needs manual business approval due to group size
        }

        // Create Reservation - Remove 'services' from creation payload (relationship handled separately)
        $reservationData = $validated;
        unset($reservationData['services']);

        $reservation = $user->reservations()->create([
            ...$reservationData,
            'location_id' => $locationId,
            'resource_id' => $resourceId,
            'price' => $totalPrice,
            'total_amount' => $totalPrice,
            'discount_amount' => $discountAmount,
            'coupon_id' => $couponId,
            'status' => $status,
            'is_recurring' => $validated['is_recurring'] ?? false,
            'recurrence_pattern' => $validated['recurrence_pattern'] ?? null,
            'recurrence_end_date' => $validated['recurrence_end_date'] ?? null,
        ]);

        // Handle Wallet Payment
        if ($validated['payment_method'] === 'wallet') {
            if ($user->balance < $totalPrice) {
                return response()->json(['message' => 'Yetersiz bakiye.'], 400);
            }

            \Illuminate\Support\Facades\DB::transaction(function () use ($user, $totalPrice, $reservation) {
                // 1. Deduct Customer Balance
                $user->decrement('balance', $totalPrice);

                // 2. Record Customer Transaction
                \App\Models\WalletTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $totalPrice,
                    'type' => 'payment',
                    'status' => 'success',
                    'description' => $reservation->business->name.' rezervasyonu için ödeme',
                    'reference_id' => $reservation->id,
                ]);

                // 3. Credit Business Owner Balance (Settlement)
                $business = $reservation->business;
                $commissionRate = $business->commission_rate ?? 1;
                $commissionAmount = ($totalPrice * $commissionRate) / 100;
                $netEarning = $totalPrice - $commissionAmount;

                $owner = $business->owner;
                if ($owner) {
                    $owner->increment('balance', $netEarning);

                    \App\Models\WalletTransaction::create([
                        'user_id' => $owner->id,
                        'amount' => $netEarning,
                        'type' => 'earning',
                        'status' => 'success',
                        'description' => "Rezervasyon Kazancı (#{$reservation->id})",
                        'reference_id' => $reservation->id,
                    ]);
                }
            });
        }

        // Attach Services
        foreach ($selectedServices as $service) {
            $reservation->menus()->attach($service['model']->id, [
                'price' => $service['price'],
                'quantity' => $service['quantity'],
            ]);
        }

        // Only send confirmation email and log 'created' activity if NOT pending_payment
        if ($status !== 'pending_payment') {
            // Send Confirmation Email
            try {
                \Illuminate\Support\Facades\Mail::to($request->user()->email)
                    ->send(new \App\Mail\ReservationConfirmed($reservation));

                // Send SMS via Twilio
                $smsService = new SmsService;
                $smsMessage = "Sayın {$user->name}, {$reservation->business->name} için rezervasyonunuz başarıyla oluşturuldu. Tarih: {$reservation->start_time->format('d.m.Y H:i')}.";
                $smsService->send($user->phone, $smsMessage);

            } catch (\Exception $e) {
                \Log::error('Failed to send confirmation email/sms: '.$e->getMessage());
            }

            // Log activity as specifically 'created/confirmed'
            ActivityLog::logReservation($reservation, 'created');
        } else {
            // Log as 'initiated' for tracking unpaid ones
            ActivityLog::logReservation($reservation, 'initiated');
        }

        // Fire Real-time Event
        try {
            \App\Events\ReservationCreated::dispatch($reservation);
        } catch (\Exception $e) {
            \Log::error('Broadcast error: '.$e->getMessage());
        }

        $response = [
            'reservation' => $reservation,
            'payment_url' => $validated['payment_method'] === 'card'
                ? \Illuminate\Support\Facades\URL::signedRoute('payment.checkout', ['reservation' => $reservation->id])
                : null,
            'message' => $validated['payment_method'] === 'card' ? 'Rezervasyon oluşturuldu, ödeme sayfasına yönlendiriliyorsunuz...' : 'Rezervasyonunuz başarıyla oluşturuldu!',
        ];

        \Log::info('RESERVATION CREATED: Payment URL: '.($response['payment_url'] ?? 'N/A'));

        return response()->json($response, 201);
    }

    /**
     * Cancel a reservation
     */
    public function cancel(Request $request, $id, \App\Services\RefundService $refundService)
    {
        $reservation = Reservation::findOrFail($id);

        // Authorization check
        if ($reservation->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Yetkisiz işlem'], 403);
        }

        // Status check - can only cancel pending or approved reservations
        if (! in_array($reservation->status, ['pending', 'approved', 'pending_payment'])) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Bu rezervasyon iptal edilemez. Durum: '.$reservation->status,
                ], 400);
            }

            return back()->with('error', 'Bu rezervasyon iptal edilemez. Mevcut durum: '.ucfirst($reservation->status));
        }

        // Cancellation policy - must be at least 24 hours before reservation
        $hoursUntilReservation = now()->diffInHours($reservation->start_time, false);

        if ($hoursUntilReservation < 24) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Rezervasyonlar en az 24 saat öncesinden iptal edilmelidir.',
                    'hours_remaining' => $hoursUntilReservation,
                ], 400);
            }

            return back()->with('error', 'Rezervasyonlar en az 24 saat öncesinden iptal edilmelidir.');
        }

        // Store info for waitlist notification before updating
        $businessId = $reservation->business_id;
        $date = $reservation->start_time->format('Y-m-d');

        // Update status to cancelled
        $reservation->update(['status' => 'cancelled']);

        // --- PHASE 2: Waitlist Logic ---
        // Notify someone from the waitlist for this business/date
        $nextInLine = \App\Models\Waitlist::where('business_id', $businessId)
            ->where('reservation_date', $date)
            ->where('status', 'waiting')
            ->orderBy('created_at', 'asc')
            ->first();

        if ($nextInLine) {
            $nextInLine->update(['status' => 'notified']);
            $nextInLine->user->notify(new \App\Notifications\WaitlistAvailableNotification($nextInLine));
        }

        // Log Activity
        ActivityLog::logReservation($reservation, 'cancelled');

        // Send cancellation email
        try {
            \Illuminate\Support\Facades\Mail::to($reservation->user->email)
                ->send(new \App\Mail\ReservationCancelled($reservation));
        } catch (\Exception $e) {
            \Log::error('Failed to send cancellation email: '.$e->getMessage());
        }

        // Send In-App Notification
        try {
            $reservation->user->notify(new \App\Notifications\ReservationStatusNotification($reservation, 'cancelled'));
        } catch (\Exception $e) {
            \Log::error('Failed to send cancellation notification: '.$e->getMessage());
        }

        // Send SMS Notification
        try {
            $smsService = new SmsService;
            $smsMessage = "Rezervist: {$reservation->business->name} için rezervasyonunuz iptal edildi.";
            $smsService->send($reservation->user->phone, $smsMessage);
        } catch (\Exception $e) {
            \Log::error('Failed to send cancellation sms: '.$e->getMessage());
        }

        // Process refund if payment was made
        if ($reservation->price > 0 && in_array($reservation->status, ['confirmed', 'cancelled'])) {
            $refundResult = $refundService->processRefund($reservation);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Rezervasyon başarıyla iptal edildi.',
                'reservation' => $reservation,
            ]);
        }

        return back()->with('success', 'Rezervasyon başarıyla iptal edildi.');
    }

    /**
     * Update reservation (reschedule)
     */
    public function update(\App\Http\Requests\UpdateReservationRequest $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $validated = $request->validated();

        // Merge date and time
        $start = \Carbon\Carbon::parse($validated['reservation_date'].' '.$validated['reservation_time']);
        $validated['start_time'] = $start->toDateTimeString();
        $validated['end_time'] = $start->copy()->addHours(2)->toDateTimeString();

        // Check for conflicts with the new time
        if ($reservation->resource_id) {
            $exists = Reservation::where('resource_id', $reservation->resource_id)
                ->where('id', '!=', $id)
                ->whereIn('status', ['confirmed', 'pending', 'approved'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('start_time', '<=', $validated['start_time'])
                                ->where('end_time', '>=', $validated['end_time']);
                        });
                })
                ->exists();

            if ($exists) {
                return response()->json(['message' => 'Seçilen zaman aralığı müsait değil.'], 409);
            }
        }

        $reservation->update($validated);

        // Send update notification email
        try {
            \Illuminate\Support\Facades\Mail::to($request->user()->email)
                ->send(new \App\Mail\ReservationConfirmed($reservation));

            // Send SMS Notification
            $smsService = new SmsService;
            $smsMessage = "Rezervist: {$reservation->business->name} rezervasyonunuz güncellendi. Yeni Tarih: {$reservation->start_time->format('d.m.Y H:i')}.";
            $smsService->send($reservation->user->phone, $smsMessage);
        } catch (\Exception $e) {
            \Log::error('Failed to send update email/sms: '.$e->getMessage());
        }

        // Log Activity
        ActivityLog::logReservation($reservation, 'updated');

        return response()->json([
            'message' => 'Rezervasyon başarıyla güncellendi.',
            'reservation' => $reservation,
        ]);
    }

    /**
     * Add user to waitlist
     */
    public function addToWaitlist(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'preferred_time_range' => 'nullable|string',
            'guest_count' => 'required|integer|min:1',
        ]);

        $waitlist = \App\Models\Waitlist::create([
            'user_id' => auth()->id(),
            'business_id' => $validated['business_id'],
            'reservation_date' => $validated['reservation_date'],
            'preferred_time_range' => $validated['preferred_time_range'],
            'guest_count' => $validated['guest_count'],
            'status' => 'waiting',
        ]);

        return response()->json([
            'message' => 'Bekleme listesine başarıyla eklendiniz. Yer açıldığında size haber vereceğiz.',
            'waitlist' => $waitlist,
        ], 201);
    }
}
