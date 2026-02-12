<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletApiController extends Controller
{
    /**
     * Get authenicated user's wallet transactions.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $transactions = $user->walletTransactions()
            ->latest()
            ->paginate(20);

        return response()->json($transactions);
    }

    /**
     * Search for users by name or phone for money transfer.
     */
    public function searchRecipients(Request $request)
    {
        $query = $request->get('q', '');
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $users = User::where('id', '!=', Auth::id())
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'phone', 'avatar']);

        return response()->json($users);
    }

    /**
     * Transfer money to another user.
     */
    public function transfer(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $sender = Auth::user();
        $recipient = User::findOrFail($request->recipient_id);
        $amount = $request->amount;

        if ($sender->id === $recipient->id) {
            return response()->json(['message' => 'Kendinize para gönderemezsiniz.'], 422);
        }

        if ($sender->balance < $amount) {
            return response()->json(['message' => 'Yetersiz bakiye.'], 422);
        }

        DB::beginTransaction();
        try {
            // Deduct from sender
            $sender->decrement('balance', $amount);
            $sender->walletTransactions()->create([
                'amount' => $amount,
                'type' => 'transfer_sent',
                'description' => $recipient->name . ' kullanıcısına transfer',
                'status' => 'success',
                'meta' => ['recipient_id' => $recipient->id]
            ]);

            // Add to recipient
            $recipient->increment('balance', $amount);
            $recipient->walletTransactions()->create([
                'amount' => $amount,
                'type' => 'transfer_received',
                'description' => $sender->name . ' kullanıcısından gelen transfer',
                'status' => 'success',
                'meta' => ['sender_id' => $sender->id]
            ]);

            DB::commit();

            // Notify recipient (optional, but good for UX)
            try {
                // $recipient->notify(new \App\Notifications\PaymentReceived($amount, $sender->name));
            } catch (\Exception $e) {}

            return response()->json([
                'success' => true,
                'message' => 'Transfer başarıyla tamamlandı.',
                'new_balance' => $sender->balance
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Transfer sırasında bir hata oluştu.'], 500);
        }
    }
}
