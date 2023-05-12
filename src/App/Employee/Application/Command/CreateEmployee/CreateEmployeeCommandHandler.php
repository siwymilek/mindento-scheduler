<?php

declare(strict_types=1);

namespace App\Employee\Application\Command\CreateEmployee;

use App\Employee\Domain\Employee;
use App\Employee\Domain\Repository\EmployeeRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\DateTimeException;

final class CreateEmployeeCommandHandler implements CommandHandlerInterface
{
    public function __construct(private readonly EmployeeRepositoryInterface $employeeRepository)
    {
    }

    /**
     * @throws DateTimeException
     */
    public function __invoke(CreateEmployeeCommand $command): void
    {
        $employee = Employee::create($command->uuid);
        $this->employeeRepository->store($employee);
    }
}
