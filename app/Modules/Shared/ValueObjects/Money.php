<?php

namespace App\Modules\Shared\ValueObjects;

use InvalidArgumentException;

final class Money
{
    private float $amount;
    private string $currency;
    public function __construct(float $amount, string $currency = 'IDR')
    {
        if ($amount < 0) {
            throw new InvalidArgumentException('Amount cannot be negative');
        }
        $this->amount = round($amount, 2);
        $this->currency = $currency;
    }
    public function getAmount(): float
    {
        return $this->amount;
    }
    public function getCurrency(): string
    {
        return $this->currency;
    }
    public function add(Money $other): self
    {
        $this->assertSameCurrency($other);
        return new self($this->amount + $other->amount, $this->currency);
    }
    public function multiply(int $quantity): self
    {
        if ($quantity < 0) {
            throw new InvalidArgumentException('Quantity cannot be 
negative');
        }
        return new self($this->amount * $quantity, $this->currency);
    }
    public function equals(Money $other): bool
    {
        return $this->currency === $other->currency
            && abs($this->amount - $other->amount) < 0.005;
    }
    public function __toString(): string
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }
    private function assertSameCurrency(Money $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('Cannot operate on different 
currencies');
        }
    }
}
