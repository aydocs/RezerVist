<?php

namespace App\Observers;

use App\Models\Order;
use App\Events\OrderSynced;

class OrderObserver
{
    /**
     * Handle the Order "saved" event.
     */
    public function saved(Order $order)
    {
        // Broadcast for real-time POS/KDS sync whenever an order is changed
        broadcast(new OrderSynced($order))->toOthers();
    }
}
