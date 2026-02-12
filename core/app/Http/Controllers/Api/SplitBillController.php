<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QrSession;
use Illuminate\Http\Request;

class SplitBillController extends Controller
{
    /**
     * Split bill equally among a number of people.
     * POST /api/qr/session/{token}/split/equal
     * Body: { person_count: int }
     */
    public function splitEqual(Request $request, string $token)
    {
        $session = QrSession::where('session_token', $token)
            ->with('order')
            ->firstOrFail();

        if (! $session->order) {
            return response()->json(['success' => false, 'message' => 'Sipariş bulunamadı.'], 404);
        }

        $request->validate(['person_count' => 'required|integer|min:2|max:20']);

        $order = $session->order;
        $remaining = $order->total_amount - $order->paid_amount;
        $perPerson = round($remaining / $request->person_count, 2);

        // Adjust last person to cover rounding difference
        $amounts = array_fill(0, $request->person_count, $perPerson);
        $amounts[count($amounts) - 1] = $remaining - ($perPerson * ($request->person_count - 1));

        return response()->json([
            'success' => true,
            'data' => [
                'total_remaining' => $remaining,
                'person_count' => $request->person_count,
                'per_person' => $perPerson,
                'amounts' => $amounts,
            ],
        ]);
    }

    /**
     * Split bill by item selection.
     * POST /api/qr/session/{token}/split/by-item
     * Body: { selected_item_ids: [int] }
     */
    public function splitByItem(Request $request, string $token)
    {
        $session = QrSession::where('session_token', $token)
            ->with('order.items')
            ->firstOrFail();

        if (! $session->order) {
            return response()->json(['success' => false, 'message' => 'Sipariş bulunamadı.'], 404);
        }

        $request->validate([
            'selected_item_ids' => 'required|array|min:1',
            'selected_item_ids.*' => 'integer',
        ]);

        $order = $session->order;
        $selectedItems = $order->items->whereIn('id', $request->selected_item_ids);

        $selectedTotal = $selectedItems->sum('total_price');

        return response()->json([
            'success' => true,
            'data' => [
                'selected_items' => $selectedItems->map(fn ($i) => [
                    'id' => $i->id,
                    'name' => $i->name,
                    'quantity' => $i->quantity,
                    'total_price' => $i->total_price,
                ])->values(),
                'selected_total' => $selectedTotal,
                'order_total' => $order->total_amount,
                'remaining_after' => $order->total_amount - $order->paid_amount - $selectedTotal,
            ],
        ]);
    }

    /**
     * Split bill with custom amounts.
     * POST /api/qr/session/{token}/split/custom
     * Body: { amount: float }
     */
    public function splitCustom(Request $request, string $token)
    {
        $session = QrSession::where('session_token', $token)
            ->with('order')
            ->firstOrFail();

        if (! $session->order) {
            return response()->json(['success' => false, 'message' => 'Sipariş bulunamadı.'], 404);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $order = $session->order;
        $remaining = $order->total_amount - $order->paid_amount;

        if ($request->amount > $remaining) {
            return response()->json([
                'success' => false,
                'message' => 'Tutar kalan hesabı aşıyor.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'amount' => $request->amount,
                'remaining_after' => $remaining - $request->amount,
                'order_total' => $order->total_amount,
            ],
        ]);
    }

    /**
     * Get payment split status for all participants.
     * GET /api/qr/session/{token}/split/status
     */
    public function splitStatus(string $token)
    {
        $session = QrSession::where('session_token', $token)
            ->with('order')
            ->firstOrFail();

        if (! $session->order) {
            return response()->json(['success' => false, 'message' => 'Sipariş bulunamadı.'], 404);
        }

        $order = $session->order;

        return response()->json([
            'success' => true,
            'data' => [
                'order_total' => $order->total_amount,
                'total_paid' => $order->paid_amount,
                'remaining' => $order->total_amount - $order->paid_amount,
                'fully_paid' => $order->payment_status === 'paid',
                'payment_status' => $order->payment_status,
            ],
        ]);
    }
}
