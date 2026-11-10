<?php
namespace App\Modules\Shared\Contracts;

interface ProductServiceInterface
{
    /** @return array{id: int, name: string, price: float, stock: int}|null 
*/
    public function getProductForOrder(int $productId): ?array;

    public function isStockAvailable(int $productId, int $quantity): bool;

    /** Harus dipanggil di dalam database transaction. */
    public function reduceStock(int $productId, int $quantity): bool;
}