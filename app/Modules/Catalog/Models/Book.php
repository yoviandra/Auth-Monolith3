<?php

namespace App\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'isbn',
        'author_id',
        'publisher_id',
        'publication_year',
        'stock',
        'price',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            LibraryCategory::class,
            'book_category',
            'book_id',
            'library_category_id'
        )->withPivot('added_at');
    }


    // Scope untuk menampilkan buku yang tersedia
    public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Scope untuk mencari buku berdasarkan judul atau ISBN
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
                ->orWhere('isbn', 'like', "%{$keyword}%");
        });
    }

    // Scope untuk memfilter buku berdasarkan kategori
    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('library_categories.id', $categoryId);
        });
    }
}
