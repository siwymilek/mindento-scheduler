<?php

declare(strict_types=1);

namespace App\Delegation\Application\Query\GetEmployeeDelegations;

use App\Shared\Application\Query\QueryInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class GetEmployeeDelegationsQuery implements QueryInterface
{
    public UuidInterface $employeeUuid;

    public function __construct(string $employeeUuid)
    {
        $this->employeeUuid = Uuid::fromString($employeeUuid);
    }
}
