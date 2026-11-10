<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Catalog\Controllers\CatalogController;

Route::get('/books', [CatalogController::class, 'index']);
Route::get('/books/{id}', [CatalogController::class, 'show']);
