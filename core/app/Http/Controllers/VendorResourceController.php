<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorResourceController extends Controller
{
    public function index(Request $request)
    {
        $business = Auth::user()->ownedBusiness;
        $locations = $business->locations;

        $resources = $business->resources()
            ->when($request->search, function ($q) use ($request) {
                return $q->where('name', 'like', '%'.$request->search.'%');
            })
            ->when($request->location_id, function ($q) use ($request) {
                if ($request->location_id === 'main') {
                    return $q->whereNull('location_id');
                }

                return $q->where('location_id', $request->location_id);
            })
            ->when($request->category, function ($q) use ($request) {
                return $q->where('category', $request->category);
            })
            ->when($request->has('status'), function ($q) use ($request) {
                if ($request->status === 'active') {
                    return $q->where('is_available', true);
                }
                if ($request->status === 'passive') {
                    return $q->where('is_available', false);
                }

                return $q;
            })
            ->with('location')
            ->when($request->sort, function ($q) use ($request) {
                if ($request->sort === 'name_asc') {
                    return $q->orderBy('name', 'asc');
                }
                if ($request->sort === 'name_desc') {
                    return $q->orderBy('name', 'desc');
                }
                if ($request->sort === 'capacity_asc') {
                    return $q->orderBy('capacity', 'asc');
                }
                if ($request->sort === 'capacity_desc') {
                    return $q->orderBy('capacity', 'desc');
                }

                return $q;
            }, function ($q) {
                return $q->latest();
            })
            ->paginate(12);

        $categories = $business->resources()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('vendor.resources.index', compact('resources', 'locations', 'categories'));
    }

    public function create()
    {
        $business = Auth::user()->ownedBusiness;
        $locations = $business->locations;

        return view('vendor.resources.create', compact('locations'));
    }

    public function createBulk()
    {
        $business = Auth::user()->ownedBusiness;
        $locations = $business->locations;

        return view('vendor.resources.bulk-create', compact('locations'));
    }

    public function storeBulk(Request $request)
    {
        $business = Auth::user()->ownedBusiness;

        $validated = $request->validate([
            'name_prefix' => 'nullable|string|max:100',
            'start_number' => 'required|integer|min:1',
            'end_number' => 'required|integer|gte:start_number',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|string|max:50',
            'location_id' => 'nullable|exists:locations,id',
            'category' => 'nullable|string|max:100',
        ]);

        $prefix = $validated['name_prefix'] ?: 'Masa';
        $locationId = $validated['location_id'];
        $type = $validated['type'];
        $capacity = $validated['capacity'];
        $category = $validated['category'];

        $count = 0;
        for ($i = $validated['start_number']; $i <= $validated['end_number']; $i++) {
            $name = $prefix.' '.$i;

            $business->resources()->create([
                'name' => $name,
                'capacity' => $capacity,
                'type' => $type,
                'location_id' => $locationId,
                'category' => $category,
                'is_available' => true,
                'stock' => 1,
                'requires_inventory' => false,
            ]);
            $count++;
        }

        return redirect()->route('vendor.resources.index')
            ->with('success', $count.' adet masa başarıyla oluşturuldu.');
    }

    public function store(Request $request)
    {
        $business = Auth::user()->ownedBusiness;
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|string|max:50',
            'is_available' => 'required|boolean',
            'location_id' => 'nullable|exists:locations,id',
            'category' => 'nullable|string|max:100',
        ]);

        $business->resources()->create($validated);

        return redirect()->route('vendor.resources.index')->with('success', 'Kaynak (Masa/Yer) başarıyla eklendi.');
    }

    public function edit(Resource $resource)
    {
        $this->authorizeBusiness($resource);
        $business = Auth::user()->ownedBusiness;
        $locations = $business->locations;

        return view('vendor.resources.edit', compact('resource', 'locations'));
    }

    public function update(Request $request, Resource $resource)
    {
        $this->authorizeBusiness($resource);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|string|max:50',
            'is_available' => 'required|boolean',
            'location_id' => 'nullable|exists:locations,id',
            'category' => 'nullable|string|max:100',
        ]);

        $resource->update($validated);

        return redirect()->route('vendor.resources.index')->with('success', 'Kaynak bilgileri güncellendi.');
    }

    public function destroy(Resource $resource)
    {
        $this->authorizeBusiness($resource);
        $resource->delete();

        return back()->with('success', 'Kaynak başarıyla silindi.');
    }

    public function bulkAction(Request $request)
    {
        $business = Auth::user()->ownedBusiness;
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:resources,id',
            'action' => 'required|in:delete,activate,deactivate',
        ]);

        $resources = Resource::whereIn('id', $validated['ids'])
            ->where('business_id', $business->id)
            ->get();

        $count = $resources->count();

        switch ($validated['action']) {
            case 'delete':
                foreach ($resources as $resource) {
                    $resource->delete();
                }
                $message = "$count adet kaynak başarıyla silindi.";
                break;
            case 'activate':
                Resource::whereIn('id', $resources->pluck('id'))->update(['is_available' => true]);
                $message = "$count adet kaynak aktif edildi.";
                break;
            case 'deactivate':
                Resource::whereIn('id', $resources->pluck('id'))->update(['is_available' => false]);
                $message = "$count adet kaynak pasif edildi.";
                break;
            default:
                return back()->with('error', 'Geçersiz işlem.');
        }

        return redirect()->route('vendor.resources.index')->with('success', $message);
    }

    private function authorizeBusiness(Resource $resource)
    {
        $business = Auth::user()->ownedBusiness;
        if ($resource->business_id !== $business->id) {
            abort(403);
        }
    }
}
