<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorSettingsController extends Controller
{
    /**
     * Show the settings page.
     */
    public function index()
    {
        $business = Auth::user()->ownedBusiness;
        if (!$business) {
            return redirect()->route('vendor.business.edit');
        }

        $resources = $business->resources()->where('is_available', true)->get();

        return view('vendor.settings.index', compact('business', 'resources'));
    }

    /**
     * Update business settings.
     */
    public function update(Request $request)
    {
        $business = Auth::user()->ownedBusiness;
        if (!$business) {
            return abort(404);
        }

        $validated = $request->validate([
            'reservations_enabled' => 'nullable|boolean',
            'qr_payments_enabled' => 'nullable|boolean',
            'table_management_enabled' => 'nullable|boolean',
            'auto_confirm' => 'nullable|boolean',
            'loyalty_enabled' => 'nullable|boolean',
            'qr_waiter_call' => 'nullable|boolean',
            'busy_mode' => 'nullable|boolean',
            'min_advance_hours' => 'nullable|integer|min:0|max:720',
            'max_ahead_days' => 'nullable|integer|min:1|max:365',
            'cancellation_limit' => 'nullable|integer|min:0|max:168',
        ]);

        // Merge with existing settings
        $settings = $business->settings ?? [];
        
        $settings['reservations_enabled'] = $request->has('reservations_enabled');
        $settings['qr_payments_enabled'] = $request->has('qr_payments_enabled');
        $settings['table_management_enabled'] = $request->has('table_management_enabled');
        $settings['auto_confirm'] = $request->has('auto_confirm');
        $settings['loyalty_enabled'] = $request->has('loyalty_enabled');
        $settings['qr_waiter_call'] = $request->has('qr_waiter_call');
        $settings['busy_mode'] = $request->has('busy_mode');
        
        $settings['min_advance_hours'] = (int) $request->input('min_advance_hours', 0);
        $settings['max_ahead_days'] = (int) $request->input('max_ahead_days', 30);
        $settings['cancellation_limit'] = (int) $request->input('cancellation_limit', 24);

        $business->update(['settings' => $settings]);

        // Handle Resource/Table Specific Reservations
        if ($request->has('resource_reservations')) {
            $resourceRes = $request->input('resource_reservations'); // [id => "1" or missing]
            
            foreach ($business->resources as $resource) {
                $enabled = isset($resourceRes[$resource->id]);
                $resource->update(['is_reservation_enabled' => $enabled]);
            }
        } else {
            // If none checked, disable all
            $business->resources()->update(['is_reservation_enabled' => false]);
        }

        return back()->with('success', 'Ayarlar başarıyla güncellendi.');
    }
}
