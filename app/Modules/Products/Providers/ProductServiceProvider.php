<?php

namespace Modules\Products\Providers;

use App\Modules\Products\Services\ProductService;
use App\Modules\Shared\Contracts\ProductServiceInterface;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductServiceInterface::class, 
ProductService::class);
    }

    public function boot(): void
    {
        // Memuat migrasi modul
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }
}
