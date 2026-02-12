<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Reservation;
use Iyzipay\Model\Refund;
use Iyzipay\Options;
use Iyzipay\Request\CreateRefundRequest;
use Iyzipay\Model\Locale;

class RefundService
{
    protected $options;

    public function __construct()
    {
        $this->options = new Options();
        $this->options->setApiKey(config('services.iyzico.api_key'));
        $this->options->setSecretKey(config('services.iyzico.secret_key'));
        $this->options->setBaseUrl(config('services.iyzico.base_url', 'https://sandbox-api.iyzipay.com'));
    }

    /**
     * Process refund for a reservation.
     * Supports both Iyzico and Wallet refunds.
     */
    public function processRefund(Reservation $reservation)
    {
        // 1. Check if there was a successful payment (Card or Wallet)
        $payment = Payment::where('reservation_id', $reservation->id)
                          ->where('status', 'success')
                          ->orderBy('created_at', 'desc')
                          ->first();

        // 2. If Card Payment found -> Refund to Wallet (As per MVP Policy)
        if ($payment) {
            return $this->forceRefundToWallet($reservation, $payment->paid_price);
        }

        // 3. If Wallet Payment found
        $walletTx = \App\Models\WalletTransaction::where('reference_id', $reservation->id)
            ->where('type', 'payment')
            ->where('status', 'success')
            ->first();

        if ($walletTx) {
            return $this->processWalletRefund($reservation, $walletTx);
        }

        \Log::warning("Refund requested for reservation #{$reservation->id} but no valid payment method found.");
        return false;
    }

    /**
     * Force refund amount to wallet (for Card payments)
     */
    protected function forceRefundToWallet(Reservation $reservation, $amount)
    {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($reservation, $amount) {
                // Credit user wallet
                $reservation->user->increment('balance', $amount);

                // Create a refund transaction
                \App\Models\WalletTransaction::create([
                    'user_id' => $reservation->user_id,
                    'amount' => $amount,
                    'type' => 'refund',
                    'status' => 'success',
                    'description' => $reservation->business->name . ' rezervasyonu iadesi (Karta iade yerine Cüzdana)',
                    'reference_id' => $reservation->id,
                ]);

                // Update reservation status in case it wasn't
                if ($reservation->status !== 'cancelled') {
                    $reservation->update(['status' => 'cancelled']);
                }
                
                // Track this "virtual" refund in Payments table too if needed, but WalletTransaction is enough for balance.
                // Optionally mark Payment as refunded internally? 
                // Let's keep the Payment as is, or maybe add a note.
            });

            \Log::info("Force Wallet Refund successful for reservation #{$reservation->id}");
            return true;
        } catch (\Exception $e) {
            \Log::error("Force Wallet Refund failed for #{$reservation->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Handle Iyzico Refund
     */
    protected function processIyzicoRefund(Reservation $reservation, Payment $payment)
    {
        $refundRequest = \App\Models\RefundRequest::create([
            'reservation_id' => $reservation->id,
            'user_id' => $reservation->user_id,
            'amount' => $payment->paid_price,
            'status' => 'pending',
            'iyzico_payment_id' => $payment->payment_id,
        ]);

        $request = new CreateRefundRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId($reservation->id);
        $request->setPaymentTransactionId($payment->payment_id);
        $request->setPrice($payment->paid_price); 
        $request->setIp(request()->ip());
        $request->setCurrency($payment->currency ?: \Iyzipay\Model\Currency::TL);

        try {
            $refund = Refund::create($request, $this->options);

            if ($refund->getStatus() == 'success') {
                $refundRequest->update([
                    'status' => 'processed',
                    'iyzico_conversation_id' => $refund->getConversationId(),
                ]);

                Payment::create([
                    'user_id' => $reservation->user_id,
                    'reservation_id' => $reservation->id,
                    'payment_id' => $refund->getPaymentId(), 
                    'conversation_id' => $refund->getConversationId(),
                    'price' => $refund->getPrice(),
                    'paid_price' => $refund->getPrice(), 
                    'status' => 'refunded', 
                    'currency' => $payment->currency ?: 'TRY',
                    'raw_result' => json_encode($refund->getRawResult()),
                ]);

                \Log::info("Iyzico Refund successful for reservation #{$reservation->id}");
                return true;
            } else {
                $refundRequest->update([
                    'status' => 'failed',
                    'admin_note' => $refund->getErrorMessage()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            $refundRequest->update(['status' => 'failed', 'admin_note' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Handle Wallet Refund
     */
    protected function processWalletRefund(Reservation $reservation, $walletTx)
    {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($reservation, $walletTx) {
                // Refund amount to user balance
                $reservation->user->increment('balance', $walletTx->amount);

                // Create a refund transaction
                \App\Models\WalletTransaction::create([
                    'user_id' => $reservation->user_id,
                    'amount' => $walletTx->amount,
                    'type' => 'refund',
                    'status' => 'success',
                    'description' => $reservation->business->name . ' rezervasyonu iadesi',
                    'reference_id' => $reservation->id,
                ]);

                // Update reservation status to reflected
                $reservation->update(['status' => 'cancelled']);
            });

            \Log::info("Wallet Refund successful for reservation #{$reservation->id}");
            return true;
        } catch (\Exception $e) {
            \Log::error("Wallet Refund failed for #{$reservation->id}: " . $e->getMessage());
            return false;
        }
    }
}
