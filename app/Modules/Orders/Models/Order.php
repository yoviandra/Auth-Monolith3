<?php
namespace App\Modules\Orders\Models;
use App\Modules\Orders\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class Order extends Model
{
    use SoftDeletes;
    // user_id adalah referensi ke Identity & Access Context, tanpa relasi 

    protected $fillable = [
        'user_id', 'order_number', 'total_amount',
        'status', 'shipping_address', 'ordered_at',
    ];
    protected $casts = [
        'total_amount' => 'decimal:2',
        'ordered_at' => 'datetime',
        'status' => OrderStatus::class,
    ];
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
    public function scopeByStatus(Builder $query, OrderStatus $status): 
Builder
    {
        return $query->where('status', $status->value);
    }
    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
    return $query->where('ordered_at', '>=', now()->subDays($days));
    }
    
    public function calculateTotal(): float
    {
    return (float) $this->items->sum('subtotal');
    }
}