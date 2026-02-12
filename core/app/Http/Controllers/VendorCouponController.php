<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorCouponController extends Controller
{
    /**
     * Display a listing of the coupons.
     */
    public function index()
    {
        $business = Auth::user()->ownedBusiness;
        if (!$business) return abort(403);

        $coupons = Coupon::where('business_id', $business->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('vendor.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create()
    {
        return view('vendor.coupons.create');
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(Request $request)
    {
        $business = Auth::user()->ownedBusiness;
        if (!$business) return abort(403);

        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code|max:20|uppercase',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:today',
        ], [
            'code.unique' => 'Bu kupon kodu daha önce kullanılmış (Global veya başka bir işletme tarafından).',
            'expires_at.after' => 'Son kullanma tarihi bugünden ileri bir tarih olmalıdır.'
        ]);

        $business->coupons()->create([
            'code' => $validated['code'],
            'type' => $validated['type'],
            'value' => $validated['value'],
            'min_amount' => $validated['min_amount'] ?? 0,
            'max_uses' => $validated['max_uses'],
            'expires_at' => $validated['expires_at'],
            'is_active' => true
        ]);

        return redirect()->route('vendor.coupons.index')
            ->with('success', 'Kupon başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit($id)
    {
        $business = Auth::user()->ownedBusiness;
        $coupon = $business->coupons()->findOrFail($id);
        
        return view('vendor.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(Request $request, $id)
    {
        $business = Auth::user()->ownedBusiness;
        $coupon = $business->coupons()->findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:20|uppercase|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:today',
        ], [
             'code.unique' => 'Bu kupon kodu zaten kullanımda.'
        ]);

        $coupon->update([
            'code' => $validated['code'],
            'type' => $validated['type'],
            'value' => $validated['value'],
            'min_amount' => $validated['min_amount'] ?? 0,
            'max_uses' => $validated['max_uses'],
            'expires_at' => $validated['expires_at'],
        ]);

        return redirect()->route('vendor.coupons.index')
            ->with('success', 'Kupon güncellendi.');
    }

    /**
     * Start/Stop (Toggle) coupon status.
     */
    public function toggleStatus($id)
    {
        $business = Auth::user()->ownedBusiness;
        $coupon = $business->coupons()->findOrFail($id);

        $coupon->update(['is_active' => !$coupon->is_active]);

        return back()->with('success', 'Kupon durumu değiştirildi.');
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy($id)
    {
        $business = Auth::user()->ownedBusiness;
        $coupon = $business->coupons()->findOrFail($id);

        $coupon->delete();

        return back()->with('success', 'Kupon silindi.');
    }
}
