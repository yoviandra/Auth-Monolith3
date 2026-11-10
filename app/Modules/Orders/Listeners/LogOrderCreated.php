<?php

namespace App\Modules\Orders\Listeners;

use App\Modules\Orders\Events\OrderCreated;
use Illuminate\Support\Facades\Log;

class LogOrderCreated
{
    public function handle(OrderCreated $event): void
    {
        Log::info('Order created', [
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'user_id' => $event->order->user_id,
            'total_amount' => $event->order->total_amount,
        ]);
    }
}