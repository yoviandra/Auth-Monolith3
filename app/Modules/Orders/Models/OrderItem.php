<?php
namespace App\Modules\Orders\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class OrderItem extends Model
{
    // product_id hanya referensi; data produk disimpan sebagai snapshot.
    protected $fillable = [
    'order_id', 'product_id', 'product_name',
    'price_at_purchase', 'quantity', 'subtotal',
    ];

    protected $casts = [
    'price_at_purchase' => 'decimal:2',
    'subtotal' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
    return $this->belongsTo(Order::class);
    }
}