<?php

declare(strict_types=1);

namespace App\Delegation\Application\Query\GetEmployeeDelegations;

use App\Delegation\Infrastructure\ReadModel\MySQL\MySQLReadModelDelegationRepository;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Application\Query\Collection;

final class GetEmployeeDelegationsQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly MySQLReadModelDelegationRepository $repository)
    {
    }

    public function __invoke(GetEmployeeDelegationsQuery $query): Collection
    {
        $employeeCollection = $this->repository->getDelegationsByEmployeeUuid($query->employeeUuid);
        return new Collection($employeeCollection);
    }
}
