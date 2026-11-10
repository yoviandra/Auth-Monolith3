<?php

namespace App\Modules\Products\Services;

use App\Modules\Products\Models\Product;
use App\Modules\Shared\Contracts\ProductServiceInterface;

class ProductService implements ProductServiceInterface
{
    public function getProductForOrder(int $productId): ?array
    {
        $product = Product::query()->where('is_active', true)->find($productId);

    if (! $product) {
    return null;
    }

    return [
        'id' => $product->id,
        'name' => $product->name,
        'price' => (float) $product->price,
        'stock' => $product->stock,
    ];

    }
    public function isStockAvailable(int $productId, int $quantity): bool
    {
        $product = Product::find($productId);

        return $product !== null && $product->isInStock($quantity);
    }

    public function reduceStock(int $productId, int $quantity): bool
    {
        if ($quantity < 1) {
            return false;
    }

    $affected = Product::query()
        ->whereKey($productId)
        ->where('is_active', true)
        ->where('stock', '>=', $quantity)
        ->decrement('stock', $quantity);

    return $affected > 0;
    }
}