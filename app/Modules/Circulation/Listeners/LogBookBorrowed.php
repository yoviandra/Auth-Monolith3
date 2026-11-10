<?php

namespace App\Modules\Circulation\Listeners;

use App\Modules\Circulation\Events\BookBorrowed;
use Illuminate\Support\Facades\Log;

class LogBookBorrowed
{
    public function handle(BookBorrowed $event): void
    {
        Log::info('Peminjaman buku berhasil.', [
            'borrowing_number' => $event->borrowing->borrowing_number,
            'member_id' => $event->borrowing->member_id,
            'borrowing_id' => $event->borrowing->id,
        ]);
    }
}