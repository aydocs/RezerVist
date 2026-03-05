<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\Resource;
use Illuminate\Http\Request;

// Assuming we have or will create resources

class PosApiController extends Controller
{
    /**
     * Get the current business context for the request.
     */
    private function getBusiness(Request $request)
    {
        // 1. Check if middleware already identified the business
        $business = $request->attributes->get('business');

        if ($business) {
            return $business;
        }

        // 2. Fallback to user relations
        $user = $request->user();
        if ($user) {
            return $user->business ?? $user->ownedBusiness;
        }

        return null;
    }

    /**
     * Check for POS updates.
     */
    public function checkVersion(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'latest_version' => config('pos.latest_version', '1.0.0'),
                'force_update' => config('pos.force_update', false),
                'download_url' => config('pos.download_url'),
                'message' => config('pos.update_message'),
            ],
        ]);
    }

    /**
     * Serve image files bypassing Cloudflare bot checks for POS external loading.
     */
    public function serveImage(Request $request)
    {
        $path = $request->query('path');
        
        if (!$path) {
            return response('No path provided', 400);
        }

        // Handle cases where frontend accidentally sends the entire full URL 
        if (str_starts_with($path, 'http')) {
            $parsed = parse_url($path);
            if (isset($parsed['path'])) {
                $path = str_replace('/storage/', '', $parsed['path']);
            }
        }

        // Clean path to prevent directory traversal
        $path = str_replace(['..', '.\\', './'], '', $path);
        
        // Allowed directories to serve from
        if (!str_starts_with($path, 'menus/') && !str_starts_with($path, 'menu_images/') && !str_starts_with($path, 'category/') && !str_starts_with($path, 'businesses/')) {
             return response('Forbidden path: ' . $path, 403);
        }

        $fullPath = storage_path('app/public/' . ltrim($path, '/'));
        
        if (!file_exists($fullPath)) {
            // Fallback to direct public path if not in storage/app/public
            $fullPath = public_path('storage/' . ltrim($path, '/'));
            if (!file_exists($fullPath)) {
                return response('Image not found', 404);
            }
        }
        
        $mime = mime_content_type($fullPath);
        
        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=86400',
            // Disable Hotlink protection limits for this endpoint
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * Initialize POS session for authenticated user.
     */
    public function init(Request $request)
    {
        $user = $request->user();
        $business = $user->business ?? $user->ownedBusiness;

        if (! $business) {
            return response()->json([
                'success' => false,
                'message' => 'İşletme hesabı bulunamadı.',
            ], 404);
        }

        // Register device fingerprint if provided
        if ($request->filled('fingerprint')) {
            $business->update([
                'device_fingerprint' => $request->input('fingerprint'),
                'device_registered_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $business->id,
                'name' => $business->name,
                'category' => $business->category,
                'description' => $business->description,
                'subscription_status' => $business->subscription_status,
                'has_master_pin' => ! empty($business->master_pin),
                'has_pos_access' => $business->hasFeature('pos_access'),
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ],
        ]);
    }

    /**
     * Deactivate POS device (Logout/Clear fingerprint).
     */
    public function deactivate(Request $request)
    {
        $user = $request->user();
        $business = $user->business ?? $user->ownedBusiness;

        if (! $business) {
            return response()->json([
                'success' => false,
                'message' => 'İşletme hesabı bulunamadı.',
            ], 404);
        }

        $business->update([
            'device_fingerprint' => null,
            'device_registered_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cihaz bağlantısı başarıyla kesildi.',
        ]);
    }

    /**
     * Get all tables with current status.
     */
    public function getTables(Request $request)
    {
        $business = $this->getBusiness($request);

        if (! $business) {
            return response()->json([
                'success' => false,
                'message' => 'İşletme hesabı bulunamadı.',
            ], 404);
        }

        // Fetch resources
        $resources = $business->resources()->get()->map(function ($resource) use ($business) {
            // Determine status - check reservations first
            $status = 'empty';
            $activeRes = $resource->reservations()
                ->whereIn('status', ['checked_in', 'confirmed'])
                ->where('start_time', '<=', now())
                ->where('end_time', '>=', now())
                ->first();

            if ($activeRes) {
                $status = $activeRes->status === 'checked_in' ? 'occupied' : 'reserved';
            }

            // Also check for active orders - explicitly pick the LATEST active one using direct query
            $activeOrder = \App\Models\Order::where('business_id', $business->id)
                ->where('resource_id', $resource->id)
                ->where('status', 'active')
                ->latest('id')
                ->first();

            if ($activeOrder) {
                $status = 'occupied';
            }

            return [
                'id' => $resource->id,
                'name' => $resource->name,
                'category' => $resource->category, // Salon, Bahçe
                'capacity' => $resource->capacity,
                'status' => $status,
                'reservation' => $activeRes ? [
                    'id' => $activeRes->id,
                    'customer_name' => $activeRes->user ? $activeRes->user->name : 'Misafir',
                    'start_time' => $activeRes->start_time,
                ] : null,
                'order' => $activeOrder ? [
                    'id' => $activeOrder->id,
                    'opened_at' => $activeOrder->opened_at,
                    'total_amount' => (float) $activeOrder->total_amount,
                    'paid_amount' => (float) ($activeOrder->paid_amount ?? 0),
                    'remaining_amount' => (float) $activeOrder->total_amount - (float) ($activeOrder->paid_amount ?? 0),
                    'payment_status' => $activeOrder->payment_status ?? 'unpaid',
                    'payment_method' => $activeOrder->payment_method,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $resources,
        ]);
    }

    /**
     * Get menu (Categories + Products).
     */
    public function getMenu(Request $request)
    {
        $business = $this->getBusiness($request);
        if (! $business) {
            return response()->json(['success' => false, 'message' => 'İşletme bulunamadı.'], 404);
        }
        $items = $business->menus()->get();

        $grouped = [];
        foreach ($items as $item) {
            $cat = trim($item->category ?? '');
            $catName = empty($cat) ? 'DİĞER' : mb_strtoupper($cat, 'UTF-8');

            if (! isset($grouped[$catName])) {
                $grouped[$catName] = [];
            }

            $grouped[$catName][] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => floatval($item->price),
                'description' => $item->description,
                'image' => $item->image ? asset('storage/'.$item->image) : null,
                'is_available' => (bool) $item->is_available,
                'options' => $item->options,
                'unit_type' => $item->unit_type ?: 'piece',
                'background_color' => $item->background_color,
            ];
        }

        $menu = [];
        foreach ($grouped as $name => $items) {
            $menu[] = [
                'name' => $name,
                'items' => $items,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $menu,
        ]);
    }

    /**
     * Get active order for a table.
     */
    public function getOrder(Request $request, $resourceId)
    {
        \Log::info("POS_GET_ORDER: Called with resourceId: {$resourceId}");

        $business = $this->getBusiness($request);

        if (! $business) {
            \Log::error('POS_GET_ORDER: Business not found for user '.($request->user() ? $request->user()->id : 'null'));

            return response()->json([
                'success' => false,
                'message' => 'İşletme hesabı bulunamadı.',
            ], 404);
        }

        \Log::info("POS_GET_ORDER: Business {$business->id}, looking for active order on resource {$resourceId}");

        $order = \App\Models\Order::where('business_id', $business->id)
            ->where('resource_id', $resourceId === 'takeaway' ? null : $resourceId)
            ->where('status', 'active')
            ->with(['items' => function ($query) {
                $query->whereNotIn('status', ['completed', 'cancelled']);
            }])
            ->latest('id')
            ->first();

        \Log::info('POS_GET_ORDER: Order query result', [
            'order_id' => $order ? $order->id : 'null',
            'items_count' => $order ? $order->items->count() : 0,
            'items_statuses' => $order ? $order->items->pluck('status') : [],
            'resource_id' => $resourceId
        ]);

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    /**
     * Create or update order for a table.
     */
    public function submitOrder(Request $request)
    {
        $business = $this->getBusiness($request);
        $resourceId = $request->input('resource_id');
        $items = $request->input('items'); // Array of { menu_id, quantity, unit_price, name, notes }

        // Find existing active order - pick the LATEST one to match getTables
        $order = \App\Models\Order::where('business_id', $business->id)
            ->where('resource_id', $resourceId === 'takeaway' ? null : $resourceId)
            ->where('status', 'active')
            ->latest('id')
            ->first();

        if (! $order) {
            $order = \App\Models\Order::create([
                'business_id' => $business->id,
                'resource_id' => $resourceId === 'takeaway' ? null : $resourceId,
                'status' => 'active',
                'opened_at' => now(),
                'payment_status' => 'unpaid',
            ]);
        }

        foreach ($items as $itemData) {
            $unitPrice = floatval($itemData['unit_price']);
            $quantity = floatval($itemData['quantity']);

            // If weight-based, quantity is in kilograms (e.g., 0.250 for 250g)
            $totalPrice = $unitPrice * $quantity;

            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $itemData['menu_id'] ?? null,
                'name' => $itemData['name'],
                'quantity' => $quantity,
                'selected_options' => $itemData['selected_options'] ?? null,
                'weight_grams' => $itemData['weight_grams'] ?? null,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'notes' => $itemData['notes'] ?? null,
                'status' => 'pending',
            ]);

            // Stock Management: Decrement stock if enabled
            if (isset($itemData['menu_id'])) {
                $menuItem = \App\Models\Menu::find($itemData['menu_id']);
                if ($menuItem && $menuItem->stock_enabled) {
                    $quantityToDecrement = $quantity;

                    $menuItem->decrement('stock_quantity', $quantityToDecrement);

                    // Check if stock is low and item should be marked unavailable
                    if ($menuItem->stock_quantity <= 0) {
                        $menuItem->update(['is_available' => false]);
                    }
                }
            }
        }

        // Update total amount explicitly
        $this->refreshOrderTotals($order);

        return response()->json([
            'success' => true,
            'data' => $order->fresh()->load(['items' => function ($q) {
                $q->whereNotIn('status', ['completed', 'cancelled']);
            }]),
        ]);
    }

    /**
     * Get all orders for the business.
     */
    public function getOrders(Request $request)
    {
        $business = $this->getBusiness($request);

        $query = \App\Models\Order::where('business_id', $business->id)
            ->with(['items', 'resource']);

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', \Carbon\Carbon::parse($request->input('start_date'))->startOfDay());
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', \Carbon\Carbon::parse($request->input('end_date'))->endOfDay());
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->limit(200)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Validate staff PIN.
     */
    public function validatePin(Request $request)
    {
        $business = $this->getBusiness($request);
        $pin = $request->input('pin');

        // 1. Check for Master PIN (8 digits)
        // Default to '00000000' if master_pin is empty
        $masterPin = $business->master_pin ?: '00000000';
        if (strlen($pin) === 8 && $masterPin === $pin) {
            return response()->json([
                'success' => true,
                'is_admin' => true,
                'data' => [
                    'id' => 0,
                    'name' => 'İşletme Sahibi',
                    'position' => 'Admin',
                    'permissions' => ['all'],
                ],
            ]);
        }

        // 2. Check for Staff PIN (4 digits)
        $staff = \App\Models\Staff::where('business_id', $business->id)
            ->where('pin_code', $pin)
            ->where('is_active', true)
            ->first();

        if (! $staff) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz PIN kodu.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'is_admin' => false,
            'data' => [
                'id' => $staff->id,
                'name' => $staff->name,
                'position' => $staff->position,
                'permissions' => $staff->permissions ?: ['take_orders', 'view_tables'],
            ],
        ]);
    }

    /**
     * Update Master PIN.
     */
    public function updateMasterPin(Request $request)
    {
        $business = $this->getBusiness($request);
        $validated = $request->validate([
            'pin' => 'required|string|size:8',
        ]);

        $business->update(['master_pin' => $validated['pin']]);

        return response()->json([
            'success' => true,
            'message' => 'Master PIN başarıyla güncellendi.',
        ]);
    }

    /**
     * Verify Master PIN for admin operations.
     */
    public function verifyMasterPin(Request $request)
    {
        $business = $this->getBusiness($request);
        $validated = $request->validate([
            'pin' => 'required|string|size:8',
        ]);

        // Check if master PIN matches
        if ($business->master_pin !== $validated['pin']) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz Master PIN.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Master PIN doğrulandı.',
        ]);
    }

    /**
     * Get financial summary with optional date filtering.
     */
    public function getDailySummary(Request $request)
    {
        $business = $this->getBusiness($request);

        if (! $business) {
            \Log::error('GetDailySummary: Business not found for user '.$request->user()->id);

            return response()->json([
                'success' => false,
                'message' => 'İşletme hesabı bulunamadı.',
            ], 404);
        }

        try {
            $startDate = $request->input('start_date') ? \Carbon\Carbon::parse($request->input('start_date'))->startOfDay() : now()->startOfDay();
            $endDate = $request->input('end_date') ? \Carbon\Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfDay();

            \Log::info("GetDailySummary: Business {$business->id}, Start: {$startDate}, End: {$endDate}");
        } catch (\Exception $e) {
            \Log::error('GetDailySummary: Date parse error: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Geçersiz tarih formatı.'], 400);
        }

        // Base query for completed items within date range
        $itemsQuery = \App\Models\OrderItem::whereHas('order', function ($q) use ($business, $startDate, $endDate) {
            $q->where('business_id', $business->id)
                ->whereBetween('updated_at', [$startDate, $endDate]);
        })
            ->where('status', 'completed');

        $totalSales = (float) $itemsQuery->sum('total_price');

        // Payment method breakdown
        $cashTotal = (float) \App\Models\OrderItem::whereHas('order', function ($q) use ($business, $startDate, $endDate) {
            $q->where('business_id', $business->id)
                ->where(function ($qq) {
                    $qq->where('payment_method', 'cash')
                        ->orWhere('payment_method', 'nakit');
                })
                ->whereBetween('updated_at', [$startDate, $endDate]);
        })
            ->where('status', 'completed')
            ->sum('total_price');

        $cardTotal = (float) \App\Models\OrderItem::whereHas('order', function ($q) use ($business, $startDate, $endDate) {
            $q->where('business_id', $business->id)
                ->where(function ($qq) {
                    $qq->where('payment_method', 'credit_card')
                        ->orWhere('payment_method', 'card')
                        ->orWhere('payment_method', 'kredi_karti');
                })
                ->whereBetween('updated_at', [$startDate, $endDate]);
        })
            ->where('status', 'completed')
            ->sum('total_price');

        // Category breakdown for charts
        $categorySales = \App\Models\OrderItem::whereHas('order', function ($q) use ($business, $startDate, $endDate) {
            $q->where('business_id', $business->id)
                ->whereBetween('updated_at', [$startDate, $endDate]);
        })
            ->where('status', 'completed')
            ->select('name', \DB::raw('SUM(quantity) as qty'), \DB::raw('SUM(total_price) as total'))
            ->groupBy('name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $summary = [
            'total_sales' => $totalSales,
            'order_count' => \App\Models\Order::where('business_id', $business->id)
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->count(),
            'cash_total' => $cashTotal,
            'card_total' => $cardTotal,
            'category_sales' => $categorySales,
            'period' => [
                'start' => $startDate->toDateTimeString(),
                'end' => $endDate->toDateTimeString(),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $summary,
        ]);
    }

    /**
     * Process payment for an order.
     */
    public function processPayment(Request $request, $orderId)
    {
        $business = $this->getBusiness($request);
        $order = \App\Models\Order::where('business_id', $business->id)
            ->where('id', $orderId)
            ->firstOrFail();

        $validated = $request->validate([
            'amount' => 'required|numeric',
            'method' => 'required|in:cash,credit_card',
            'is_partial' => 'nullable|boolean',
            'item_ids' => 'nullable|array',
            'discount' => 'nullable|numeric',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $amount = floatval($validated['amount']);
            $discount = floatval($validated['discount'] ?? 0);
            $isPartial = $validated['is_partial'] ?? false;
            $itemIds = $validated['item_ids'] ?? [];

            // 1. Update paid amount and handle discount
            // Note: We will force a full recalculation from items later anyway,
            // but we keep the transactional update for consistent state.
            if ($discount > 0) {
                $order->decrement('total_amount', $discount);
            }

            if (! $isPartial) {
                $order->update([
                    'paid_amount' => $order->total_amount,
                    'payment_status' => 'paid',
                    'payment_method' => $validated['method'],
                    'status' => 'completed',
                    'closed_at' => now(),
                ]);

                // Mark all items as completed
                $order->items()->whereNotIn('status', ['cancelled', 'deleted'])->update([
                    'status' => 'completed',
                    'updated_at' => now(),
                ]);
            } else {
                // Partial or Itemized
                if (! empty($itemIds)) {
                    $counts = array_count_values($itemIds);
                    \Log::info("POS_PAYMENT: Processing itemized payment for Order {$order->id}. Item counts: ".json_encode($counts));

                    foreach ($counts as $itemId => $paidCount) {
                        // Use fresh query to avoid stale collection state
                        $item = \App\Models\OrderItem::where('order_id', $order->id)->where('id', $itemId)->first();

                        if ($item && $item->status !== 'completed') {
                            $currentQty = floatval($item->quantity);
                            \Log::info("POS_PAYMENT: Splitting Item {$itemId}. Current Qty: {$currentQty}, Paid Count: {$paidCount}");

                            if ($currentQty > $paidCount) {
                                // Split: Decrement original
                                $newQty = $currentQty - $paidCount;
                                $item->update([
                                    'quantity' => $newQty,
                                    'total_price' => $item->unit_price * $newQty,
                                ]);

                                // Create new row for the paid portion
                                $paidItem = $item->replicate();
                                $paidItem->quantity = $paidCount;
                                $paidItem->total_price = $item->unit_price * $paidCount;
                                $paidItem->status = 'completed';
                                $paidItem->save();
                                \Log::info("POS_PAYMENT: Split complete. Original ID {$itemId} now has Qty {$newQty}. New ID {$paidItem->id} created with Qty {$paidCount} (completed).");
                            } else {
                                // Full row paid
                                $item->update([
                                    'status' => 'completed',
                                    'updated_at' => now(),
                                ]);
                                \Log::info("POS_PAYMENT: Full item {$itemId} marked as completed.");
                            }
                        }
                    }
                    // Recalculate if everything is paid
                    $unpaidItemsCount = \App\Models\OrderItem::where('order_id', $order->id)
                        ->whereNotIn('status', ['completed', 'cancelled', 'deleted'])
                        ->count();

                    if ($unpaidItemsCount === 0) {
                        $order->update([
                            'payment_status' => 'paid',
                            'payment_method' => $validated['method'],
                            'status' => 'completed',
                            'closed_at' => now(),
                        ]);
                    }
                }
            }

            // CRITICAL: Final robust recalculation
            $this->refreshOrderTotals($order);

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ödeme başarıyla kaydedildi.',
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Ödeme hatası: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Refresh order summary columns (total_amount, paid_amount) based on current items.
     */
    private function refreshOrderTotals(&$order)
    {
        if (! $order) {
            return;
        }

        // Recalculate total_amount (All items except cancelled/deleted)
        $total = (float) \App\Models\OrderItem::where('order_id', $order->id)
            ->whereNotIn('status', ['cancelled', 'deleted'])
            ->sum(\DB::raw('unit_price * quantity'));

        // Recalculate paid_amount (Only completed items)
        $paid = (float) \App\Models\OrderItem::where('order_id', $order->id)
            ->where('status', 'completed')
            ->sum(\DB::raw('unit_price * quantity'));

        // Determine payment status
        $paymentStatus = 'unpaid';
        if ($paid >= $total && $total > 0) {
            $paymentStatus = 'paid';
        } elseif ($paid > 0) {
            $paymentStatus = 'partial';
        }

        // Update DB
        $order->update([
            'total_amount' => $total,
            'paid_amount' => $paid,
            'payment_status' => $paymentStatus,
        ]);

        // Update in-memory for immediate use in response
        $order->total_amount = $total;
        $order->paid_amount = $paid;
        $order->payment_status = $paymentStatus;
    }

    /**
     * Get all staff for the business.
     */
    public function getStaff(Request $request)
    {
        $business = $this->getBusiness($request);
        $staff = \App\Models\Staff::where('business_id', $business->id)
            ->where('is_active', true)
            ->get(['id', 'name', 'position', 'pin_code', 'permissions']);

        return response()->json([
            'success' => true,
            'data' => $staff,
        ]);
    }

    /**
     * Update staff permissions.
     */
    public function updateStaffPermissions(Request $request, $id)
    {
        $business = $this->getBusiness($request);
        $staff = \App\Models\Staff::where('business_id', $business->id)
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'permissions' => 'required|array',
        ]);

        $staff->update([
            'permissions' => $validated['permissions'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Yetkiler başarıyla güncellendi.',
        ]);
    }

    /**
     * Update staff PIN.
     */
    public function updateStaffPin(Request $request, $id)
    {
        $business = $this->getBusiness($request);
        $staff = \App\Models\Staff::where('business_id', $business->id)
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'pin' => 'required|string|size:4',
        ]);

        $staff->update([
            'pin_code' => $validated['pin'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'PIN kodu başarıyla güncellendi.',
        ]);
    }

    /**
     * Store a new menu item.
     */
    public function storeMenuItem(Request $request)
    {
        $business = $this->getBusiness($request);
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu_items', 'public');
        }

        $options = $request->input('options');
        if (is_string($options)) {
            $options = json_decode($options, true);
        }

        $item = \App\Models\Menu::create([
            'business_id' => $business->id,
            'name' => $validated['name'],
            'price' => $validated['price'],
            'category' => mb_strtoupper(trim($validated['category']), 'UTF-8'),
            'description' => $validated['description'] ?? null,
            'options' => $options,
            'unit_type' => $request->input('unit_type', 'piece'),
            'image' => $imagePath,
            'background_color' => $request->input('background_color'),
            'stock_enabled' => $request->boolean('stock_enabled', false),
            'stock_quantity' => $request->input('stock_quantity'),
            'low_stock_alert' => $request->input('low_stock_alert'),
            'is_available' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ürün başarıyla eklendi.',
            'data' => $item,
        ]);
    }

    /**
     * Update a menu item.
     */
    public function updateMenuItem(Request $request, $id)
    {
        $business = $this->getBusiness($request);
        $item = \App\Models\Menu::where('business_id', $business->id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $validated;
        unset($data['image']);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($item->image) {
                \Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('menu_items', 'public');
        }

        $options = $request->input('options');
        if (is_string($options)) {
            $options = json_decode($options, true);
        }

        $data['options'] = $options;
        $data['unit_type'] = $request->input('unit_type', 'piece');
        $data['background_color'] = $request->input('background_color');
        $data['stock_enabled'] = $request->boolean('stock_enabled', false);
        $data['stock_quantity'] = $request->input('stock_quantity');
        $data['low_stock_alert'] = $request->input('low_stock_alert');
        $data['category'] = mb_strtoupper(trim($validated['category']), 'UTF-8');

        $item->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Ürün güncellendi.',
        ]);
    }

    /**
     * Update an order item (e.g. change quantity, partial void).
     */
    public function updateOrderItem(Request $request, $orderId, $itemId)
    {
        $business = $this->getBusiness($request);
        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string', // For audit log if needed
        ]);

        $order = \App\Models\Order::where('business_id', $business->id)
            ->where('id', $orderId)
            ->firstOrFail();

        $item = \App\Models\OrderItem::where('order_id', $order->id)
            ->where('id', $itemId)
            ->firstOrFail();

        if ($item->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Ödenmiş ürün güncellenemez. İptal işlemi yapmalısınız.',
            ], 422);
        }

        $oldQty = floatval($item->quantity);
        $newQty = floatval($validated['quantity']);
        $diff = $newQty - $oldQty;

        if ($diff == 0) {
            return response()->json(['success' => true, 'message' => 'Değişiklik yok.', 'data' => $order]);
        }

        // Stock Management
        if ($item->menu_id) {
            $menuItem = \App\Models\Menu::find($item->menu_id);
            if ($menuItem && $menuItem->stock_enabled) {
                if ($diff > 0) {
                    // Increasing quantity - Consume stock
                    if ($menuItem->stock_quantity < $diff) {
                        return response()->json(['success' => false, 'message' => 'Yetersiz stok.'], 422);
                    }
                    $menuItem->decrement('stock_quantity', $diff);
                } else {
                    // Decreasing quantity - Restore stock
                    $menuItem->increment('stock_quantity', abs($diff));
                }
            }
        }

        // Update Item
        $item->update([
            'quantity' => $newQty,
            'total_price' => $item->unit_price * $newQty,
            'updated_at' => now(),
        ]);

        // Recalculate totals
        $this->refreshOrderTotals($order);

        return response()->json([
            'success' => true,
            'message' => 'Ürün güncellendi.',
            'data' => $order->fresh()->load(['items' => function ($q) {
                $q->whereNotIn('status', ['completed', 'cancelled', 'deleted']);
            }]),
        ]);
    }

    /**
     * Delete an order item (remove from order).
     */
    public function deleteOrderItem(Request $request, $orderId, $itemId)
    {
        $business = $this->getBusiness($request);

        $order = \App\Models\Order::where('business_id', $business->id)
            ->where('id', $orderId)
            ->firstOrFail();

        $item = \App\Models\OrderItem::where('order_id', $order->id)
            ->where('id', $itemId)
            ->firstOrFail();

        if ($item->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Ödenmiş ürün silinemez. İptal işlemi yapmalısınız.',
            ], 422);
        }

        // Soft delete by changing status
        $item->update([
            'status' => 'deleted',
            'updated_at' => now(),
        ]);

        // Recalculate totals
        $this->refreshOrderTotals($order);

        // Check if order has any valid items left
        $validItemsCount = \App\Models\OrderItem::where('order_id', $order->id)
            ->whereNotIn('status', ['cancelled', 'deleted'])
            ->count();

        if ($validItemsCount === 0) {
            // No valid items left (all deleted or cancelled)
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Siparişte ürün kalmadığı için sipariş/masa kapatıldı.',
                'order_deleted' => true,
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ürün siparişten silindi.',
            'order_deleted' => false,
            'data' => $order->fresh()->load(['items' => function ($q) {
                $q->whereNotIn('status', ['completed', 'cancelled', 'deleted']);
            }]),
        ]);
    }

    /**
     * Delete a menu item.
     */
    public function deleteMenuItem(Request $request, $id)
    {
        $business = $this->getBusiness($request);
        $item = \App\Models\Menu::where('business_id', $business->id)->findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ürün silindi.',
        ]);
    }

    /**
     * Store a new staff member.
     */
    public function storeStaff(Request $request)
    {
        $business = $this->getBusiness($request);
        $validated = $request->validate([
            'name' => 'required|string',
            'position' => 'required|string',
            'pin_code' => 'required|string|size:4',
            'permissions' => 'nullable|array',
        ]);

        // Unique check within business
        $exists = \App\Models\Staff::where('business_id', $business->id)
            ->where('pin_code', $validated['pin_code'])
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Bu PIN kodu zaten başka bir personel tarafından kullanılıyor.'], 422);
        }

        $staff = \App\Models\Staff::create([
            'business_id' => $business->id,
            'name' => $validated['name'],
            'position' => $validated['position'],
            'pin_code' => $validated['pin_code'],
            'permissions' => $validated['permissions'] ?? ['take_orders', 'view_tables'],
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Personel başarıyla eklendi.',
            'data' => $staff,
        ]);
    }

    /**
     * Delete a staff member.
     */
    public function deleteStaff(Request $request, $id)
    {
        $business = $this->getBusiness($request);
        $staff = \App\Models\Staff::where('business_id', $business->id)->findOrFail($id);
        $staff->delete();

        return response()->json([
            'success' => true,
            'message' => 'Personel silindi.',
        ]);
    }

    /**
     * Store a new resource (table).
     */
    public function storeResource(Request $request)
    {
        $business = $this->getBusiness($request);
        $validated = $request->validate([
            'name' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'category' => 'required|string',
        ]);

        $resource = \App\Models\Resource::create([
            'business_id' => $business->id,
            'name' => $validated['name'],
            'capacity' => $validated['capacity'],
            'category' => mb_strtoupper($validated['category'], 'UTF-8'),
            'is_available' => true,
            'type' => 'table',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Masa başarıyla eklendi.',
            'data' => $resource,
        ]);
    }

    /**
     * Update a resource.
     */
    public function updateResource(Request $request, $id)
    {
        $business = $this->getBusiness($request);
        $resource = \App\Models\Resource::where('business_id', $business->id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'category' => 'required|string',
        ]);

        $resource->update([
            'name' => $validated['name'],
            'capacity' => $validated['capacity'],
            'category' => mb_strtoupper($validated['category'], 'UTF-8'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Masa güncellendi.',
        ]);
    }

    /**
     * Delete a resource.
     */
    public function deleteResource(Request $request, $id)
    {
        $business = $this->getBusiness($request);
        $resource = \App\Models\Resource::where('business_id', $business->id)->findOrFail($id);

        if ($resource->orders()->where('status', 'active')->exists()) {
            return response()->json(['success' => false, 'message' => 'Açık siparişi olan masa silinemez.'], 422);
        }

        $resource->delete();

        return response()->json([
            'success' => true,
            'message' => 'Masa silindi.',
        ]);
    }

    public function transferTable(Request $request)
    {
        $validated = $request->validate([
            'from_table_id' => 'required|exists:resources,id',
            'to_table_id' => 'required|exists:resources,id',
        ]);

        $fromTableId = $validated['from_table_id'];
        $toTableId = $validated['to_table_id'];

        if ($fromTableId == $toTableId) {
            return response()->json(['success' => false, 'message' => 'Kaynak ve hedef masa aynı olamaz.']);
        }

        // Find active or confirmed order for source table
        $sourceOrder = \App\Models\Order::where('resource_id', $fromTableId)
            ->whereIn('status', ['active', 'confirmed'])
            ->with('items')
            ->latest()
            ->first();

        if (! $sourceOrder) {
            return response()->json(['success' => false, 'message' => 'Taşınacak masada açık sipariş bulunamadı.']);
        }

        // Check target table
        $targetOrder = \App\Models\Order::where('resource_id', $toTableId)
            ->whereIn('status', ['active', 'confirmed'])
            ->latest()
            ->first();

        \DB::beginTransaction();
        try {
            if (! $targetOrder) {
                // MOVE OPERATION: Target is empty, just update the resource_id
                $sourceOrder->resource_id = $toTableId;
                $sourceOrder->save();

                $message = 'Masa başarıyla taşındı.';
            } else {
                // MERGE OPERATION: Target is occupied, move items
                foreach ($sourceOrder->items as $item) {
                    $item->order_id = $targetOrder->id;
                    $item->save();
                }

                // Recalculate target order total using the armored logic
                $this->refreshOrderTotals($targetOrder);

                // Delete source order
                $sourceOrder->delete();

                $message = 'Masalar başarıyla birleştirildi.';
            }

            \DB::commit();

            return response()->json(['success' => true, 'message' => $message]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'İşlem sırasında hata oluştu: '.$e->getMessage()]);
        }
    }

    /**
     * Get all active orders for Kitchen Display System
     */
    public function getKitchenOrders(Request $request)
    {
        $business = $this->getBusiness($request);

        $orders = \App\Models\Order::where('business_id', $business->id)
            ->whereIn('status', ['active', 'confirmed'])
            ->where('payment_status', 'unpaid')
            ->with(['resource', 'items' => function ($query) {
                $query->whereNotIn('status', ['completed', 'cancelled'])
                    ->with('menu'); // Load menu details for category
            }])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Update order item status (for KDS)
     */
    public function updateItemStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed',
        ]);

        $item = \App\Models\OrderItem::findOrFail($id);
        $item->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Durum güncellendi.',
            'data' => $item,
        ]);
    }

    /**
     * Get all Happy Hours for the business
     */
    public function getHappyHours(Request $request)
    {
        $business = $this->getBusiness($request);
        $happyHours = \App\Models\HappyHour::where('business_id', $business->id)->get();

        return response()->json([
            'success' => true,
            'data' => $happyHours,
        ]);
    }

    /**
     * Get currently active Happy Hours
     */
    public function getActiveHappyHours(Request $request)
    {
        $business = $request->user()->business;
        $allHappyHours = \App\Models\HappyHour::where('business_id', $business->id)
            ->where('is_active', true)
            ->get();

        $activeNow = $allHappyHours->filter(function ($hh) {
            return $hh->isActiveNow();
        });

        return response()->json([
            'success' => true,
            'data' => $activeNow->values(),
        ]);
    }

    /**
     * Create new Happy Hour
     */
    public function storeHappyHour(Request $request)
    {
        $business = $request->user()->business;
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:percentage,fixed_amount,bogo',
            'discount_value' => 'nullable|numeric',
            'start_time' => 'required',
            'end_time' => 'required',
            'days_of_week' => 'required|array',
            'applicable_categories' => 'nullable|array',
            'applicable_items' => 'nullable|array',
        ]);

        $happyHour = \App\Models\HappyHour::create([
            'business_id' => $business->id,
            ...$validated,
        ]);

        return response()->json([
            'success' => true,
            'data' => $happyHour,
        ]);
    }

    /**
     * Update Happy Hour
     */
    public function updateHappyHour(Request $request, $id)
    {
        $business = $request->user()->business;
        $happyHour = \App\Models\HappyHour::where('business_id', $business->id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:percentage,fixed_amount,bogo',
            'discount_value' => 'nullable|numeric',
            'start_time' => 'required',
            'end_time' => 'required',
            'days_of_week' => 'required|array',
            'applicable_categories' => 'nullable|array',
            'applicable_items' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $happyHour->update($validated);

        return response()->json([
            'success' => true,
            'data' => $happyHour,
        ]);
    }

    /**
     * Delete Happy Hour
     */
    public function deleteHappyHour(Request $request, $id)
    {
        $business = $request->business;
        $happyHour = \App\Models\HappyHour::where('business_id', $business->id)->findOrFail($id);
        $happyHour->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Advanced Analytics: Top Selling Products
     */
    public function getTopProducts(Request $request)
    {
        $business = $request->business;
        $days = $request->input('days', 7); // Default to last 7 days

        $topProducts = \App\Models\OrderItem::whereHas('order', function ($q) use ($business, $days) {
            $q->where('business_id', $business->id)
                ->where('created_at', '>=', now()->subDays($days))
                ->where('payment_status', 'paid');
        })
            ->selectRaw('name, SUM(quantity) as total_quantity, SUM(total_price) as total_revenue, COUNT(DISTINCT order_id) as order_count')
            ->groupBy('name')
            ->orderBy('total_revenue', 'DESC')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $topProducts,
        ]);
    }

    /**
     * Advanced Analytics: Hourly Sales Distribution
     */
    public function getHourlySales(Request $request)
    {
        $business = $request->business;

        $hourlySales = \App\Models\Order::where('business_id', $business->id)
            ->where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as order_count, SUM(total_amount) as revenue')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $hourlySales,
        ]);
    }

    /**
     * Advanced Analytics: Weekly Revenue Trend
     */
    public function getWeeklyTrend(Request $request)
    {
        $business = $request->business;

        $weeklyData = \App\Models\Order::where('business_id', $business->id)
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as order_count, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $weeklyData,
        ]);
    }

    /**
     * Advanced Analytics: Payment Method Breakdown
     */
    public function getPaymentBreakdown(Request $request)
    {
        $business = $request->business;
        $days = $request->input('days', 7);

        $breakdown = \App\Models\Order::where('business_id', $business->id)
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $breakdown,
        ]);
    }
}
