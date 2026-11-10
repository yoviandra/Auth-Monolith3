<?php
namespace App\Modules\Shared\Exceptions;
use DomainException;
class InsufficientStockException extends DomainException
{
public function __construct(
public readonly string $productName,
public readonly int $available,
public readonly int $requested,
) {
parent::__construct(
"Stok {$productName} tidak mencukupi (tersedia: {$available}, 
diminta: {$requested})"
);
}
}