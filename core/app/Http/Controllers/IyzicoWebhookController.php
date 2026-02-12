<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;

class IyzicoWebhookController extends Controller
{
    /**
     * Handle incoming webhooks from Iyzico.
     * Documentation: https://dev.iyzipay.com/tr/webhooks
     */
    public function handle(Request $request)
    {
        $payload = $request->all();
        
        Log::info('Iyzico Webhook Received:', $payload);

        // Security: In a real production system, you MUST verify the signature.
        // Iyzico sends an 'x-iyzi-signature' header.
        // For This MVP/Development, we will process based on paymentId.
        
        if (!isset($payload['iyziEventType']) || !isset($payload['paymentId'])) {
            return response()->json(['status' => 'invalid_payload'], 400);
        }

        $paymentId = $payload['paymentId'];
        $eventType = $payload['iyziEventType']; // e.g., PAY_WITH_IYZICO_SUCCESS, REFUND

        $payment = Payment::where('payment_id', $paymentId)->first();

        if (!$payment) {
            Log::warning("Iyzico Webhook: Payment not found for ID {$paymentId}");
            return response()->json(['status' => 'not_found'], 404);
        }

        switch ($eventType) {
            case 'PAY_WITH_IYZICO_SUCCESS':
            case 'AUTH':
                $this->handleSuccess($payment);
                break;
            case 'REFUND':
                $this->handleRefund($payment);
                break;
            default:
                Log::info("Iyzico Webhook: Unhandled event type {$eventType}");
                break;
        }

        return response()->json(['status' => 'success']);
    }

    protected function handleSuccess(Payment $payment)
    {
        if ($payment->status !== 'success') {
            $payment->update(['status' => 'success']);
            $payment->reservation->update(['status' => 'approved']);
            
            Log::info("Iyzico Webhook: Payment #{$payment->id} marked as success.");
        }
    }

    protected function handleRefund(Payment $payment)
    {
        if ($payment->status !== 'refunded') {
            $payment->update(['status' => 'refunded']);
            $payment->reservation->update(['status' => 'cancelled']);
            
            Log::info("Iyzico Webhook: Payment #{$payment->id} marked as refunded.");
        }
    }
}
