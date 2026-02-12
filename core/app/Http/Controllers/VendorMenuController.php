<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorMenuController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;

        if (!$business) {
            return redirect()->route('vendor.business.edit');
        }

        $menus = $business->menus()->latest()->paginate(10);

        return view('vendor.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('vendor.menus.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $business = $user->ownedBusiness;

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string', // Starter, Main, Service, etc.
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price', 'category']);
        $data['business_id'] = $business->id;
        // In this logic, 'price' in DB is the final price shown to customer? 
        // OR 'price' is vendor price? 
        // User said: "product 100 lira company enters 100 lira and rezerve et commission is added on top so users buy it company knows this"
        // So DB should probably store the BASE price (100) and we calculate display price on fly? 
        // OR store final price and base price?
        // Let's store the VENDOR PRICE (100) in 'price' column for now as the core value. 
        // But wait, if we change commission later, old prices shouldn't change? 
        // Actually, usually you store the price the customer pays. But for simplicity let's stick to storing what the vendor entered, and calculate commission. 
        // Better: Store `price` (Vendor Price) and maybe we can add a helper or just calc on blade.
        
        // I'll stick to storing the 'price' entered by vendor.
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu_images', 'public');
        }

        $business->menus()->create($data);

        return redirect()->route('vendor.menus.index')->with('success', 'Menü/Hizmet başarıyla eklendi.');
    }

    public function edit(Menu $menu)
    {
        // Policy check
        if ($menu->business_id !== Auth::user()->ownedBusiness->id) {
            abort(403);
        }
        return view('vendor.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        if ($menu->business_id !== Auth::user()->ownedBusiness->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price', 'category']);

        if ($request->hasFile('image')) {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $data['image'] = $request->file('image')->store('menu_images', 'public');
        }

        $menu->update($data);

        return redirect()->route('vendor.menus.index')->with('success', 'Güncellendi.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->business_id !== Auth::user()->ownedBusiness->id) {
            abort(403);
        }

        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return back()->with('success', 'Silindi.');
    }
}
