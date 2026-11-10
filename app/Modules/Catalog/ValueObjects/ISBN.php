<?php

namespace App\Modules\Catalog\ValueObjects;

use InvalidArgumentException;

class ISBN
{
    private string $value;

    public function __construct(string $isbn)
    {
        $isbn = str_replace(['-', ' '], '', $isbn);

        if (!$this->isValid($isbn)) {
            throw new InvalidArgumentException('ISBN tidak valid.');
        }

        $this->value = $isbn;
    }

    private function isValid(string $isbn): bool
    {
        // Validasi ISBN-13
        if (strlen($isbn) === 13 && ctype_digit($isbn)) {
            $sum = 0;

            for ($i = 0; $i < 12; $i++) {
                $sum += (int) $isbn[$i] * ($i % 2 === 0 ? 1 : 3);
            }

            return (10 - ($sum % 10)) % 10 === (int) $isbn[12];
        }

        // Validasi ISBN-10
        if (strlen($isbn) === 10) {
            $sum = 0;

            for ($i = 0; $i < 10; $i++) {
                $digit = $isbn[$i] === 'X' ? 10 : $isbn[$i];

                if (!ctype_digit((string) $digit) && $digit !== 10) {
                    return false;
                }

                $sum += (int) $digit * (10 - $i);
            }

            return $sum % 11 === 0;
        }

        return false;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
