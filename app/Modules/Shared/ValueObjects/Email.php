<?php

namespace App\Modules\Shared\ValueObjects;

use InvalidArgumentException;

final class Email
{
    private string $value;
    public function __construct(string $value)
    {
        $value = trim($value);
        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email: {$value}");
        }
        $this->value = strtolower($value);
    }
    public function getValue(): string
    {
        return $this->value;
    }
    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }
    public function __toString(): string
    {
        return $this->value;
    }
}
