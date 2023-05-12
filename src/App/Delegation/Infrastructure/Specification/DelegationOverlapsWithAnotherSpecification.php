<?php

declare(strict_types=1);

namespace App\Delegation\Infrastructure\Specification;

use App\Delegation\Domain\Exception\DelegationOverlappingWithAnotherException;
use App\Delegation\Domain\Repository\GetDelegationsInPeriodRepositoryInterface;
use App\Delegation\Domain\Specification\DelegationOverlapsWithAnotherSpecificationInterface;
use App\Delegation\Domain\ValueObject\Period;
use Ramsey\Uuid\UuidInterface;

class DelegationOverlapsWithAnotherSpecification implements DelegationOverlapsWithAnotherSpecificationInterface
{
    public function __construct(private readonly GetDelegationsInPeriodRepositoryInterface $repository)
    {
    }

    public function isOverlapping(UuidInterface $employeeUuid, Period $period): bool
    {
        if (count($this->repository->getDelegationsInPeriod($employeeUuid, $period))) {
            throw new DelegationOverlappingWithAnotherException('The employee is in the delegation on a specific date and time.');
        }

        return true;
    }
}
