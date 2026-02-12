<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\QrSession;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QrTableController extends Controller
{
    /**
     * Start a QR session for a table.
     * POST /api/qr/session
     * Body: { business_id, resource_id }  (or parsed from QR token)
     */
    public function startSession(Request $request)
    {
        $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'resource_id' => 'required|exists:resources,id',
        ]);

        $business = Business::findOrFail($request->business_id);
        $resource = Resource::where('id', $request->resource_id)
            ->where('business_id', $business->id)
            ->firstOrFail();

        // Check for existing active session on this table for this user
        $userId = auth()->id();
        $existing = QrSession::where('resource_id', $resource->id)
            ->where('business_id', $business->id)
            ->where('user_id', $userId)
            ->active()
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'data' => $this->formatSession($existing),
                'message' => 'Mevcut oturum bulundu.',
            ]);
        }

        // Find or create an active order for this table
        $order = Order::where('resource_id', $resource->id)
            ->where('business_id', $business->id)
            ->where('status', 'open')
            ->first();

        $session = QrSession::create([
            'business_id' => $business->id,
            'resource_id' => $resource->id,
            'user_id' => $userId,
            'order_id' => $order?->id,
            'session_token' => QrSession::generateToken(),
            'status' => 'active',
            'expires_at' => now()->addHours(4),
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->formatSession($session),
        ]);
    }

    /**
     * Get active session details.
     * GET /api/qr/session/{token}
     */
    public function getSession(string $token)
    {
        $session = QrSession::where('session_token', $token)
            ->with(['business', 'resource', 'order.items'])
            ->firstOrFail();

        if ($session->isExpired()) {
            $session->update(['status' => 'expired']);

            return response()->json([
                'success' => false,
                'message' => 'Oturum süresi dolmuş.',
            ], 410);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatSession($session),
        ]);
    }

    /**
     * Get the menu for the session's business.
     * GET /api/qr/session/{token}/menu
     */
    public function getMenu(string $token)
    {
        $session = QrSession::where('session_token', $token)->active()->firstOrFail();
        $business = $session->business;

        $menus = $business->menus()
            ->where('is_available', true)
            ->with(['items' => function ($q) {
                $q->where('is_available', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'business_name' => $business->name,
                'table_name' => $session->resource->name ?? 'Masa',
                'categories' => $menus->map(function ($menu) {
                    return [
                        'id' => $menu->id,
                        'name' => $menu->name,
                        'items' => $menu->items->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'name' => $item->name,
                                'price' => $item->price,
                                'description' => $item->description,
                                'image' => $item->image,
                                'is_available' => $item->is_available,
                            ];
                        }),
                    ];
                }),
            ],
        ]);
    }

    /**
     * Submit an order from the QR session.
     * POST /api/qr/session/{token}/order
     * Body: { items: [{ menu_item_id, quantity, notes? }] }
     */
    public function submitOrder(Request $request, string $token)
    {
        $session = QrSession::where('session_token', $token)->active()->firstOrFail();

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($request, $session) {
            // Create or get existing open order for this table
            $order = Order::firstOrCreate(
                [
                    'resource_id' => $session->resource_id,
                    'business_id' => $session->business_id,
                    'status' => 'open',
                ],
                [
                    'total_amount' => 0,
                    'paid_amount' => 0,
                    'payment_status' => 'pending',
                    'opened_at' => now(),
                ]
            );

            $totalAdded = 0;
            foreach ($request->items as $item) {
                $menuItem = \App\Models\MenuItem::findOrFail($item['menu_item_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'name' => $menuItem->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $menuItem->price,
                    'total_price' => $menuItem->price * $item['quantity'],
                    'notes' => $item['notes'] ?? null,
                    'status' => 'pending',
                ]);
                $totalAdded += $menuItem->price * $item['quantity'];
            }

            // Update order total
            $order->increment('total_amount', $totalAdded);

            // Link session to order
            $session->update([
                'order_id' => $order->id,
                'status' => 'ordering',
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $order->id,
                    'items_added' => count($request->items),
                    'amount_added' => $totalAdded,
                    'order_total' => $order->fresh()->total_amount,
                ],
                'message' => 'Siparişiniz alındı!',
            ]);
        });
    }

    /**
     * Get the bill for the session.
     * GET /api/qr/session/{token}/bill
     */
    public function getBill(string $token)
    {
        $session = QrSession::where('session_token', $token)
            ->with(['order.items', 'business', 'resource'])
            ->firstOrFail();

        if (! $session->order) {
            return response()->json([
                'success' => false,
                'message' => 'Henüz sipariş verilmemiş.',
            ], 404);
        }

        $order = $session->order;

        return response()->json([
            'success' => true,
            'data' => [
                'business_name' => $session->business->name,
                'table_name' => $session->resource->name ?? 'Masa',
                'order_id' => $order->id,
                'items' => $order->items->map(function ($item) {
                    return [
                        'name' => $item->name,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->total_price,
                        'status' => $item->status,
                    ];
                }),
                'total_amount' => $order->total_amount,
                'paid_amount' => $order->paid_amount,
                'remaining' => $order->total_amount - $order->paid_amount,
                'payment_status' => $order->payment_status,
            ],
        ]);
    }

    /**
     * Pay the bill (or partial payment) from mobile.
     * POST /api/qr/session/{token}/pay
     * Body: { amount?, payment_method: 'wallet'|'credit_card' }
     */
    public function payBill(Request $request, string $token)
    {
        $session = QrSession::where('session_token', $token)
            ->with(['order', 'business'])
            ->firstOrFail();

        if (! $session->order) {
            return response()->json(['success' => false, 'message' => 'Sipariş bulunamadı.'], 404);
        }

        $request->validate([
            'payment_method' => 'required|in:wallet,credit_card',
            'amount' => 'nullable|numeric|min:0.01',
        ]);

        $order = $session->order;
        $remaining = $order->total_amount - $order->paid_amount;
        $payAmount = $request->amount ?? $remaining;

        if ($payAmount > $remaining) {
            return response()->json(['success' => false, 'message' => 'Ödeme tutarı kalan hesabı aşıyor.'], 422);
        }

        $user = auth()->user();

        if ($request->payment_method === 'wallet') {
            // Check wallet balance
            if (($user->balance ?? 0) < $payAmount) {
                return response()->json(['success' => false, 'message' => 'Yetersiz bakiye.'], 422);
            }

            // Deduct from wallet
            $user->decrement('balance', $payAmount);

            // Credit business
            $businessOwner = $session->business->owner;
            if ($businessOwner) {
                $businessOwner->increment('balance', $payAmount * 0.95); // 5% platform fee
            }
        }

        // Update order payment
        $order->increment('paid_amount', $payAmount);
        $session->increment('total_paid', $payAmount);

        if ($order->fresh()->paid_amount >= $order->total_amount) {
            $order->update([
                'payment_status' => 'paid',
                'payment_method' => 'mobile_'.$request->payment_method,
                'status' => 'closed',
                'closed_at' => now(),
            ]);
            $session->markCompleted();
        } else {
            $session->update(['status' => 'paying']);
        }

        // Notify business owner
        $businessOwner = $session->business->owner;
        if ($businessOwner) {
            $businessOwner->notify(new \App\Notifications\MobilePaymentNotification([
                'table_name' => $session->resource->name ?? 'Masa',
                'amount' => $payAmount,
                'payment_method' => $request->payment_method,
                'order_id' => $order->id,
                'fully_paid' => $order->fresh()->payment_status === 'paid',
            ]));
        }

        return response()->json([
            'success' => true,
            'data' => [
                'paid_amount' => $payAmount,
                'total_paid' => $session->fresh()->total_paid,
                'remaining' => $order->fresh()->total_amount - $order->fresh()->paid_amount,
                'fully_paid' => $order->fresh()->payment_status === 'paid',
                'receipt' => [
                    'business_name' => $session->business->name,
                    'table' => $session->resource->name ?? 'Masa',
                    'date' => now()->format('d.m.Y H:i'),
                    'items' => $order->items->map(fn ($i) => [
                        'name' => $i->name,
                        'qty' => $i->quantity,
                        'price' => $i->total_price,
                    ]),
                    'total' => $order->total_amount,
                    'paid' => $payAmount,
                    'method' => $request->payment_method === 'wallet' ? 'Cüzdan' : 'Kredi Kartı',
                ],
            ],
            'message' => 'Ödeme başarılı! ✨',
        ]);
    }

    /**
     * Format session for API response.
     */
    private function formatSession(QrSession $session): array
    {
        $session->loadMissing(['business', 'resource', 'order.items']);

        return [
            'id' => $session->id,
            'session_token' => $session->session_token,
            'status' => $session->status,
            'business' => [
                'id' => $session->business->id,
                'name' => $session->business->name,
                'image' => $session->business->image,
            ],
            'table' => [
                'id' => $session->resource->id,
                'name' => $session->resource->name ?? 'Masa',
            ],
            'order' => $session->order ? [
                'id' => $session->order->id,
                'total' => $session->order->total_amount,
                'paid' => $session->order->paid_amount,
                'items_count' => $session->order->items->count(),
            ] : null,
            'expires_at' => $session->expires_at?->toIso8601String(),
        ];
    }
}
