<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Reservation;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaiterKioskController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;

        if (! $business || ! $business->waiter_kiosk_enabled) {
            return redirect()->route('vendor.dashboard')->with('error', 'Kiosk modu bu işletme için aktif değil.');
        }

        // Get all resources with today's reservations
        $resources = $business->resources()->with(['reservations' => function ($q) {
            $q->whereDate('start_time', now()->toDateString())
                ->whereNotIn('status', ['cancelled', 'rejected'])
                ->with('user');
        }])->orderBy('name')->get();

        // Get today's active reservations
        $activeReservations = $business->reservations()
            ->whereDate('start_time', now()->toDateString())
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->with(['user', 'resource'])
            ->orderBy('start_time', 'asc')
            ->get();

        // Get upcoming reservations (next 2 hours)
        $upcomingReservations = $business->reservations()
            ->whereDate('start_time', now()->toDateString())
            ->where('status', 'confirmed')
            ->whereBetween('start_time', [now(), now()->addHours(2)])
            ->with(['user', 'resource'])
            ->orderBy('start_time', 'asc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_tables' => $resources->count(),
            'occupied_tables' => $resources->filter(function ($r) {
                return $r->reservations->some(fn ($res) => $res->status === 'checked_in');
            })->count(),
            'reserved_tables' => $resources->filter(function ($r) {
                return $r->reservations->some(fn ($res) => $res->status === 'confirmed');
            })->count(),
            'total_guests_today' => $business->reservations()
                ->whereDate('start_time', now()->toDateString())
                ->whereIn('status', ['confirmed', 'checked_in', 'completed'])
                ->sum('guest_count'),
            'current_guests' => $business->reservations()
                ->where('status', 'checked_in')
                ->sum('guest_count'),
            'reservations_today' => $business->reservations()
                ->where('status', '!=', 'pending_payment')
                ->whereDate('start_time', now()->toDateString())
                ->count(),
        ];

        return view('vendor.kiosk', compact('business', 'resources', 'activeReservations', 'upcomingReservations', 'stats'));
    }

    public function checkIn(Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $reservation->update(['status' => 'checked_in']);

        // Log Activity
        \App\Models\ActivityLog::logActivity(
            'reservation_checked_in',
            "Rezervasyon kiosk'tan check-in yapıldı: #{$reservation->id}",
            ['reservation_id' => $reservation->id, 'resource' => $reservation->resource->name ?? 'N/A']
        );

        return back()->with('success', 'Giriş yapıldı.');
    }

    public function checkOut(Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $reservation->update(['status' => 'completed']);

        // Reward loyalty points upon completion if not already done
        if ($reservation->user && $reservation->business->isLoyaltyEnabled()) {
            $amount = $reservation->total_amount > 0 ? $reservation->total_amount : $reservation->price;
            $points = (int) $amount;
            if ($points > 0) {
                $reservation->user->rewardPoints($points);
            }
        }

        // Log Activity
        \App\Models\ActivityLog::logActivity(
            'reservation_completed',
            "Rezervasyon kiosk'tan tamamlandı: #{$reservation->id}",
            ['reservation_id' => $reservation->id, 'resource' => $reservation->resource->name ?? 'N/A']
        );

        return back()->with('success', 'Çıkış yapıldı ve tamamlandı.');
    }

    public function quickBook(Request $request)
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;

        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'guest_count' => 'required|integer|min:1',
            'customer_name' => 'nullable|string|max:100',
        ]);

        // Verify resource belongs to business
        $resource = \App\Models\Resource::findOrFail($validated['resource_id']);
        if ($resource->business_id !== $business->id) {
            return back()->with('error', 'Bu kaynak işletmenize ait değil.');
        }

        // Check capacity
        if ($validated['guest_count'] > $resource->capacity) {
            return back()->with('error', 'Kişi sayısı kapasite aşıyor.');
        }

        $startTime = now();
        $endTime = $startTime->copy()->addHours(2); // Default 2 hour duration

        $reservation = $business->reservations()->create([
            'user_id' => null, // Walk-in
            'resource_id' => $validated['resource_id'],
            'guest_count' => $validated['guest_count'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'checked_in',
            'price' => 0,
            'total_amount' => 0,
            'payment_status' => 'pending',
            'note' => 'Walk-in: '.($validated['customer_name'] ?? 'Hızlı Müşteri'),
        ]);

        // Log Activity
        \App\Models\ActivityLog::logActivity(
            'reservation_walk_in',
            "Kiosk'tan hızlı masa açıldı: {$resource->name}",
            ['reservation_id' => $reservation->id, 'resource' => $resource->name, 'guests' => $validated['guest_count']]
        );

        return back()->with('success', "Hızlı rezervasyon oluşturuldu - {$resource->name}");
    }
}
