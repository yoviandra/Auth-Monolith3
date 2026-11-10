<?php

namespace App\Modules\Circulation\Events;

use App\Modules\Circulation\Models\Borrowing;

class BookBorrowed
{
    public function __construct(
        public Borrowing $borrowing
    ) {}
}