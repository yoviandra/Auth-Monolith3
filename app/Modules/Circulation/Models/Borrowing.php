<?php

namespace App\Modules\Circulation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Membership\Models\Member;

class Borrowing extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'borrowing_number',
        'member_id',
        'borrowed_at',
        'due_date',
        'status',
    ];

    protected $casts = [
        'borrowed_at' => 'date',
        'due_date' => 'date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(BookReturn::class, 'borrowing_id');
    }
}
