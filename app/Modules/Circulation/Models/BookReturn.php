<?php

namespace App\Modules\Circulation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookReturn extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'borrowing_id',
        'returned_at',
        'condition',
    ];

    protected $casts = [
        'returned_at' => 'date',
    ];

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }
}
