<?php

declare(strict_types=1);

namespace App\Employee\Infrastructure\ReadModel\Projections;

use App\Employee\Domain\Event\EmployeeWasCreated;
use App\Employee\Infrastructure\ReadModel\EmployeeReadModel;
use App\Employee\Infrastructure\ReadModel\MySQL\MySQLReadModelEmployeeRepository;
use App\Shared\Domain\Exception\DateTimeException;
use Broadway\ReadModel\Projector;

final class EmployeeProjectionFactory extends Projector
{
    public function __construct(private readonly MySQLReadModelEmployeeRepository $repository)
    {
    }

    /**
     * @throws DateTimeException
     */
    protected function applyEmployeeWasCreated(EmployeeWasCreated $employeeWasCreated): void
    {
        $employeeReadModel = EmployeeReadModel::fromSerializable($employeeWasCreated);
        $this->repository->add($employeeReadModel);
    }
}
