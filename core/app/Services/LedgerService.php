<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LedgerService
{
    /**
     * Record a financial transaction for a user.
     * 
     * @param User $user The user performing the transaction
     * @param float $amount The amount (positive for credit/deposit, negative for debit/payment)
     * @param string $type The transaction type (deposit, payment, refund, withdraw)
     * @param string|null $description
     * @param string|null $referenceId
     * @param array $metaData
     * @return WalletTransaction
     * @throws \Exception
     */
    public function record(User $user, float $amount, string $type, ?string $description = null, ?string $referenceId = null, array $metaData = [])
    {
        return DB::transaction(function () use ($user, $amount, $type, $description, $referenceId, $metaData) {
            // 1. Lock the user row to prevent race conditions (Section 5.3)
            $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();

            $currentBalance = (float) $lockedUser->balance;
            $newBalance = round($currentBalance + $amount, 2);

            // 2. Prevent negative balance if it's a payment/withdrawal
            if ($newBalance < 0 && in_array($type, ['payment', 'withdraw'])) {
                throw new \Exception('Yetersiz bakiye.');
            }

            // 3. Create the Transaction entry (Immutable Ledger - Section 5.2)
            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'balance_after' => $newBalance,
                'type' => $type,
                'status' => 'success',
                'description' => $description,
                'reference_id' => $referenceId,
                'meta_data' => $metaData,
            ]);

            // 4. Update the user's cached balance
            $lockedUser->update(['balance' => $newBalance]);

            Log::info("Ledger Entry: User {$user->id}, Amount {$amount}, Type {$type}, New Balance {$newBalance}");

            return $transaction;
        });
    }

    /**
     * Handle Split Payment (Section 5.4)
     * 
     * @param User $user
     * @param float $totalAmount Total paid by user
     * @param float $commissionRate Business commission rate (e.g. 5.0 for 5%)
     * @param string|null $orderId
     */
    public function processSplitPayment(User $user, float $totalAmount, float $commissionRate, ?string $orderId = null)
    {
        return DB::transaction(function () use ($user, $totalAmount, $commissionRate, $orderId) {
            $commissionAmount = round($totalAmount * ($commissionRate / 100), 2);
            $vendorNet = $totalAmount - $commissionAmount;

            // 1. Deduct from user wallet
            $this->record($user, -$totalAmount, 'payment', "Sipariş Ödemesi: #{$orderId}", $orderId, [
                'splits' => [
                    'vendor_net' => $vendorNet,
                    'commission' => $commissionAmount,
                    'commission_rate' => $commissionRate
                ]
            ]);

            // Note: In an ideal world, the Vendor (Business) would also have a Ledger account.
            // For now, we log this in the customer's meta_data and could extend this to a VendorBalance model.
            
            return true;
        });
    }
}
