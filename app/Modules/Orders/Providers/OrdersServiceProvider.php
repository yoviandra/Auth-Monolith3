<?php

namespace App\Modules\Orders\Providers;

use App\Modules\Orders\Events\OrderCreated;
use App\Modules\Orders\Listeners\LogOrderCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class OrdersServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
        Event::listen(OrderCreated::class, LogOrderCreated::class);
    }
}
