<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;

class VendorLocationController extends Controller
{
    private function authorizeBusiness()
    {
        $business = Auth::user()->ownedBusiness;
        if (!$business) {
            abort(403, 'İşletme sahibi değilsiniz.');
        }
        return $business;
    }

    public function index()
    {
        $business = $this->authorizeBusiness();
        $locations = $business->locations()->paginate(10);
        return view('vendor.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('vendor.locations.create');
    }

    public function store(Request $request)
    {
        $business = $this->authorizeBusiness();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $business->locations()->create($validated);

        return redirect()->route('vendor.locations.index')->with('success', 'Şube başarıyla eklendi.');
    }

    public function edit($id)
    {
        $business = $this->authorizeBusiness();
        $location = $business->locations()->findOrFail($id);
        return view('vendor.locations.edit', compact('location'));
    }

    public function update(Request $request, $id)
    {
        $business = $this->authorizeBusiness();
        $location = $business->locations()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_active' => 'required|boolean',
        ]);

        $location->update($validated);

        return redirect()->route('vendor.locations.index')->with('success', 'Şube güncellendi.');
    }

    public function destroy($id)
    {
        $business = $this->authorizeBusiness();
        $location = $business->locations()->findOrFail($id);
        $location->delete();

        return redirect()->route('vendor.locations.index')->with('success', 'Şube silindi.');
    }
}
