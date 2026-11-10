<?php

namespace App\Modules\Catalog\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Book;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::with([
            'author',
            'publisher',
            'categories'
        ])
        ->available()
        ->when($request->search, function ($query, $search) {
            $query->search($search);
        })
        ->when($request->category_id, function ($query, $categoryId) {
            $query->byCategory($categoryId);
        })
        ->get();

        return response()->json($books);
    }

    public function show($id)
    {
        $book = Book::with([
            'author',
            'publisher',
            'categories'
        ])->findOrFail($id);

        return response()->json($book);
    }
}
