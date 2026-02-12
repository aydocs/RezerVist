<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        $business = auth()->user()->ownedBusiness;

        if (!$business || strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];

        // 1. Search Reservations (by Customer Name or ID)
        $reservations = \App\Models\Reservation::where('business_id', $business->id)
            ->where(function($q) use ($query) {
                $q->where('id', 'like', "%{$query}%")
                  ->orWhereHas('user', function($uq) use ($query) {
                      $uq->where('name', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%");
                  });
            })
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        foreach ($reservations as $res) {
            $results[] = [
                'type' => 'Rezervasyon',
                'title' => '#' . $res->id . ' - ' . $res->user->name,
                'subtitle' => \Carbon\Carbon::parse($res->start_time)->format('d.m.Y H:i') . ' | ' . $res->status,
                'url' => route('vendor.reservations.index') . '?reservation_id=' . $res->id,
                'icon' => 'calendar-check'
            ];
        }

        // 2. Search Staff
        $staff = \App\Models\Staff::where('business_id', $business->id)
            ->where('name', 'like', "%{$query}%")
            ->take(5)
            ->get();

        foreach ($staff as $s) {
            $results[] = [
                'type' => 'Personel',
                'title' => $s->name,
                'subtitle' => $s->position ?? 'Çalışan',
                'url' => route('vendor.staff.index'), // Assuming there's a staff index
                'icon' => 'user-tie'
            ];
        }

        // 3. Search Resources
        $resources = \App\Models\Resource::where('business_id', $business->id)
            ->where('name', 'like', "%{$query}%")
            ->take(5)
            ->get();

        foreach ($resources as $r) {
            $results[] = [
                'type' => 'Varlık / Masa',
                'title' => $r->name,
                'subtitle' => $r->type . ' | ' . $r->capacity . ' Kişilik',
                'url' => route('vendor.resources.index'), // Assuming there's a resources index
                'icon' => 'chair'
            ];
        }

        return response()->json($results);
    }
}
