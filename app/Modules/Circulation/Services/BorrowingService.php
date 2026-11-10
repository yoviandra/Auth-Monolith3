<?php

namespace App\Modules\Circulation\Services;

use App\Modules\Catalog\Models\Book;
use App\Modules\Membership\Models\Member;
use App\Modules\Circulation\Models\Borrowing;
use App\Modules\Circulation\Events\BookBorrowed;
use Illuminate\Support\Facades\DB;
use Exception;

class BorrowingService
{
    public function borrow(Member $member, array $items)
    {
        // Menjalankan proses peminjaman dalam transaction
        $borrowing = DB::transaction(function () use ($member, $items) {

            $borrowing = Borrowing::create([
                'borrowing_number' => 'BRW-' . time(),
                'member_id' => $member->id,
                'borrowed_at' => now(),
                'due_date' => now()->addDays(7),
                'status' => 'borrowed',
            ]);

            foreach ($items as $item) {
                $book = Book::lockForUpdate()
                    ->findOrFail($item['book_id']);

                if ($book->stock < $item['quantity']) {
                    throw new Exception(
                        'Stok buku ' . $book->title . ' tidak mencukupi.'
                    );
                }

                $book->decrement('stock', $item['quantity']);

                $borrowing->details()->create([
                    'book_id' => $book->id,
                    'quantity' => $item['quantity'],
                ]);
            }

            $member->update([
                'status' => 'borrowed',
            ]);

            return $borrowing;
            
        });

        // Event dijalankan setelah transaction berhasil
        event(new BookBorrowed($borrowing));

        return $borrowing;
    }

    
}