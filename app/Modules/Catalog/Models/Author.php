<?php

namespace App\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'bio',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
