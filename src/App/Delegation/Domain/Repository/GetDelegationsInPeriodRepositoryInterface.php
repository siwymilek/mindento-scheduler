<?php

declare(strict_types=1);

namespace App\Delegation\Domain\Repository;

use App\Delegation\Domain\ValueObject\Period;
use Ramsey\Uuid\UuidInterface;

interface GetDelegationsInPeriodRepositoryInterface
{
    public function getDelegationsInPeriod(UuidInterface $employeeUuid, Period $period): array;
}
