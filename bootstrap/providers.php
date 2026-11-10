<?php

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    App\Providers\AppServiceProvider::class,
    Modules\Auth\Providers\AuthServiceProvider::class,
    Modules\Products\Providers\ProductServiceProvider::class,
    App\Modules\Orders\Providers\OrdersServiceProvider::class,
];
