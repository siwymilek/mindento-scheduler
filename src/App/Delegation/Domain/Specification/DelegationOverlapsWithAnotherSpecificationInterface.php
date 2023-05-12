<?php

declare(strict_types=1);

namespace App\Delegation\Domain\Specification;

use App\Delegation\Domain\ValueObject\Period;
use Ramsey\Uuid\UuidInterface;

interface DelegationOverlapsWithAnotherSpecificationInterface
{
    public function isOverlapping(UuidInterface $employeeUuid, Period $period): bool;
}
