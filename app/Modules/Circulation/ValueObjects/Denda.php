<?php

namespace App\Modules\Circulation\ValueObjects;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class Denda
{
    private int $lateDays;
    private int $amount;

    public function __construct(
        CarbonInterface $dueDate,
        CarbonInterface $returnedDate,
        int $dailyFine = 1000
    ) {
        $this->lateDays = max(
            0,
            (int) $dueDate->copy()->startOfDay()
                ->diffInDays($returnedDate->copy()->startOfDay(), false)
        );

        $this->amount = $this->lateDays * $dailyFine;
    }

    public function lateDays(): int
    {
        return $this->lateDays;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function formatted(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}