<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdrawal;
use App\Models\WalletTransaction;

class WithdrawalAdminController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::with('user')->latest()->paginate(20);
        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function update(Request $request, Withdrawal $withdrawal)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,completed',
            'decline_reason' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $withdrawal->status;
        $withdrawal->update([
            'status' => $request->status,
            'decline_reason' => $request->decline_reason,
            'processed_at' => in_array($request->status, ['completed', 'rejected']) ? now() : $withdrawal->processed_at,
        ]);

        // If rejected, refund the balance to the user
        if ($request->status === 'rejected' && $oldStatus !== 'rejected') {
            $user = $withdrawal->user;
            $user->increment('balance', $withdrawal->amount);

            WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $withdrawal->amount,
                'type' => 'topup', // Or 'refund'
                'status' => 'success',
                'description' => 'Para çekme talebi reddedildi (İade)',
                'reference_id' => $withdrawal->id,
            ]);
        }

        return back()->with('success', 'Talep başarıyla güncellendi.');
    }
}
