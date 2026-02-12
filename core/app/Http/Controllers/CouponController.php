<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Validate a coupon code and return its value.
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', $request->code)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json(['message' => 'Geçersiz kupon kodu.'], 404);
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return response()->json(['message' => 'Kuponun süresi dolmuş.'], 400);
        }

        if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
            return response()->json(['message' => 'Kupon kullanım limiti dolmuş.'], 400);
        }

        if ($request->amount < $coupon->min_amount) {
            return response()->json([
                'message' => "Bu kuponu kullanmak için minimum harfama tutarı: {$coupon->min_amount} TL"
            ], 400);
        }

        $discount = $coupon->calculateDiscount($request->amount);

        return response()->json([
            'valid' => true,
            'code' => $coupon->code,
            'discount_amount' => $discount,
            'new_total' => max(0, $request->amount - $discount),
            'type' => $coupon->type,
            'value' => $coupon->value
        ]);
    }
}
