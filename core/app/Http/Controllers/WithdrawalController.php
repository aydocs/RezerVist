<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'amount' => 'required|numeric|min:100', // Minimum 100 TL
            'iban' => 'required|string|size:26|starts_with:TR',
            'account_holder' => 'required|string|max:255',
            'bank_name' => 'nullable|string|max:255',
        ]);

        if ($user->balance < $request->amount) {
            return back()->with('error', 'Yetersiz bakiye.');
        }

        DB::transaction(function () use ($user, $request) {
            // 1. Create Withdrawal Request
            Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'iban' => $request->iban,
                'account_holder' => $request->account_holder,
                'bank_name' => $request->bank_name,
                'status' => 'pending',
            ]);

            // 2. Lock/Deduct balance immediately
            $user->decrement('balance', $request->amount);

            // 3. Record Transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => -$request->amount,
                'type' => 'payment', // Or 'withdrawal' if we add a new type
                'status' => 'success',
                'description' => 'Para çekme talebi oluşturuldu',
            ]);
        });

        return back()->with('success', 'Para çekme talebiniz başarıyla oluşturuldu. İncelendikten sonra hesabınıza aktarılacaktır.');
    }
}
