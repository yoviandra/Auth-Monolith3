<?php
namespace App\Modules\Orders\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class OrderStatusHistory extends Model
{
// changed_by adalah referensi user_id, tanpa relasi Eloquent ke User.
protected $fillable = ['order_id', 'status', 'notes', 'changed_by'];
public function order(): BelongsTo
{
return $this->belongsTo(Order::class);
}
}