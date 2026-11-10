<?php

namespace App\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LibraryCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    // Relasi ke kategori induk
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // Relasi ke subkategori
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    // Relasi kategori dengan buku
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(
            Book::class,
            'book_category',
            'library_category_id',
            'book_id'
        )->withPivot('added_at');
    }
}
