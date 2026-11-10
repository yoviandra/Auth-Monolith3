<?php

namespace App\Modules\Circulation\Services;

use App\Modules\Circulation\Models\Borrowing;
use App\Modules\Circulation\Models\BookReturn;
use App\Modules\Circulation\Models\Fine;
use App\Modules\Circulation\ValueObjects\Denda;
use Illuminate\Support\Facades\DB;

class ReturnService
{
    public function returnBook(Borrowing $borrowing)
    {
        return DB::transaction(function () use ($borrowing) {

            $returnedDate = now();

            // Mencatat pengembalian buku
            $borrowing->returns()->create([
                'returned_at' => $returnedDate,
                'condition' => 'Baik',
            ]);

            // Menghitung denda
            $denda = new Denda(
                $borrowing->due_date,
                $returnedDate
            );

            // Menyimpan denda jika terlambat
            if ($denda->amount() > 0) {
                Fine::create([
                    'borrowing_id' => $borrowing->id,
                    'amount' => $denda->amount(),
                    'status' => 'unpaid',
                    'description' => 'Denda keterlambatan ' .
                        $denda->lateDays() . ' hari',
                ]);
            }

            // Mengembalikan stok buku
            foreach ($borrowing->details as $detail) {
                $detail->book()->increment(
                    'stock',
                    $detail->quantity
                );
            }

            // Memperbarui status peminjaman
            $borrowing->update([
                'status' => 'returned',
            ]);

            $borrowing->member()->update(['status' => 'active']);

            return $denda;
        });
    }
}