<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $business = Auth::user()->ownedBusiness;
        $locations = $business->locations;

        $staff = Staff::where('business_id', $business->id)
            ->when($request->location_id, function ($q) use ($request) {
                if ($request->location_id === 'main') {
                    return $q->whereNull('location_id');
                }

                return $q->where('location_id', $request->location_id);
            })
            ->with('location')
            ->latest()
            ->paginate(10);

        return view('vendor.staff.index', compact('staff', 'locations'));
    }

    public function create()
    {
        $business = Auth::user()->ownedBusiness;
        $locations = $business->locations;

        return view('vendor.staff.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $business = Auth::user()->ownedBusiness;
        $subscription = $business->activeSubscription;
        $limit = -1; // Default to unlimited if no subscription found

        if ($subscription && $subscription->package) {
            $limit = $subscription->package->features['staff_limit'] ?? 2; // Default 2 for Starter/Free if not set
        }

        if ($limit !== -1) {
            $currentStaffCount = $business->staff()->count();
            if ($currentStaffCount >= $limit) {
                return back()->with('error', "Mevcut paketinizde maksimum {$limit} personel ekleyebilirsiniz. Lütfen paketinizi yükseltin.");
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'photo' => 'nullable|image|max:2048',
            'location_id' => 'nullable|exists:locations,id',
        ]);

        if ($request->hasFile('photo')) {
            $paths = FileUploadService::uploadImage($request->file('photo'), 'staff_photos');
            $validated['photo_path'] = $paths['original'];
        }

        $business->staff()->create($validated);

        return redirect()->route('vendor.staff.index')->with('success', 'Personel başarıyla eklendi.');
    }

    public function edit(Staff $staff)
    {
        $this->authorizeBusiness($staff);
        $business = Auth::user()->ownedBusiness;
        $locations = $business->locations;

        return view('vendor.staff.edit', compact('staff', 'locations'));
    }

    public function update(Request $request, Staff $staff)
    {
        $this->authorizeBusiness($staff);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'photo' => 'nullable|image|max:2048',
            'is_active' => 'required|boolean',
            'location_id' => 'nullable|exists:locations,id',
        ]);

        if ($request->hasFile('photo')) {
            if ($staff->photo_path) {
                FileUploadService::delete($staff->photo_path);
            }
            $paths = FileUploadService::uploadImage($request->file('photo'), 'staff_photos');
            $validated['photo_path'] = $paths['original'];
        }

        $staff->update($validated);

        return redirect()->route('vendor.staff.index')->with('success', 'Personel bilgileri güncellendi.');
    }

    public function destroy(Staff $staff)
    {
        $this->authorizeBusiness($staff);
        $staff->delete();

        return back()->with('success', 'Personel silindi.');
    }

    public function toggleStatus(Staff $staff)
    {
        $this->authorizeBusiness($staff);
        $staff->update(['is_active' => ! $staff->is_active]);

        return back()->with('success', 'Personel durumu güncellendi.');
    }

    private function authorizeBusiness(Staff $staff)
    {
        $business = Auth::user()->ownedBusiness;
        if ($staff->business_id !== $business->id) {
            abort(403);
        }
    }
}
