<?php

declare(strict_types=1);

namespace App\Delegation\Domain\Calculator;

interface CalculatorInterface
{
    public function getTotalAllowance(): int;
}
