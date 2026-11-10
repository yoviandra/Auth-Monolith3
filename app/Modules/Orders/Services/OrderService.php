<?php
namespace App\Modules\Orders\Services;

use App\Modules\Orders\Enums\OrderStatus;
use App\Modules\Orders\Events\OrderCreated;
use App\Modules\Orders\Events\OrderStatusChanged;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderItem;
use App\Modules\Orders\Models\OrderStatusHistory;
use App\Modules\Shared\Contracts\ProductServiceInterface;
use App\Modules\Shared\Exceptions\InsufficientStockException;
use App\Modules\Shared\ValueObjects\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class OrderService
{
    public function __construct(
        private ProductServiceInterface $productService
    ) {
    }

    /**
     * @param array<int, array{product_id: int, quantity: int}> $items
     */
    public function createOrder(int $userId, array $items, ?string 
$shippingAddress = null): Order
     {
        $lines = $this->normalizeItems($items);
        return DB::transaction(function () use ($userId, $lines, 
$shippingAddress) {
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => $this->generateOrderNumber(),
                'total_amount' => 0,
                'status' => OrderStatus::Pending,
                'shipping_address' => $shippingAddress,
                'ordered_at' => now(),
            ]);

            $total = new Money(0);

            foreach ($lines as $productId => $quantity) {
                $product = $this->productService->getProductForOrder($productId);

                if ($product === null) {
                    throw new InvalidArgumentException(
                        "Produk tidak ditemukan atau tidak aktif: 
{$productId}"
                    );
                }

                if (! $this->productService->reduceStock($productId, 
$quantity)) {
                throw new InsufficientStockException(
                        $product['name'],
                        $product['stock'],
                        $quantity
                    );
            }

            $subtotal = (new Money($product['price']))->multiply($quantity);

            OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product['id'],
                    'product_name' => $product['name'],
                    'price_at_purchase' => $product['price'],
                    'quantity' => $quantity,
                    'subtotal' => $subtotal->getAmount(),
                ]);

                $total = $total->add($subtotal);
            }
            $order->update(['total_amount' => $total->getAmount()]);
            $order->load('items');

            // Invariant aggregate: total = jumlah subtotal item.
            if (! (new Money($order->calculateTotal()))->equals($total)) {
                throw new RuntimeException('Total order tidak sama dengan 
jumlah subtotal item');
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => OrderStatus::Pending->value,
                'notes' => 'Order created',
                'changed_by' => $userId,
            ]);

            event(new OrderCreated($order));

            return $order->load('items', 'statusHistories');
        });
    }

    public function updateStatus(
        Order $order,
        OrderStatus $newStatus,
        int $changedBy,
        ?string $notes = null
    ): Order {
        return DB::transaction(function () use ($order, $newStatus, 
$changedBy, $notes) {
            $locked = Order::query()->lockForUpdate()->findOrFail($order->id);
            $oldStatus = $locked->status;

            if (! $oldStatus->canTransitionTo($newStatus)) {
                throw new InvalidArgumentException(
                    "Transisi status {$oldStatus->value} ke {$newStatus->value} tidak diizinkan");
            }

            $locked->update(['status' => $newStatus]);
            OrderStatusHistory::create([
                'order_id' => $locked->id,
                'status' => $newStatus->value,
                'notes' => $notes,
                'changed_by' => $changedBy,
            ]);

            event(new OrderStatusChanged($locked, $oldStatus, $newStatus));

            return $locked->fresh('statusHistories');
        });
    }

    /**
     * Menggabungkan item duplikat dan mengurutkannya berdasarkan product_id.
     *
     * @return array<int, int> product_id => quantity
     */
    private function normalizeItems(array $items): array
    {
        if ($items === []) {
            throw new InvalidArgumentException('Order harus memiliki minimal 
1 item');
        }

        $lines = [];

        foreach ($items as $item) {
            $productId = (int) ($item['product_id'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 0);

            if ($productId < 1 || $quantity < 1) {
                throw new InvalidArgumentException('product_id dan quantity 
minimal bernilai 1');
            }

            $lines[$productId] = ($lines[$productId] ?? 0) + $quantity;
        }

        ksort($lines);

        return $lines;
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-' . now()->format('Ymd') . '-' . 
strtoupper(Str::random(8));
    }
}