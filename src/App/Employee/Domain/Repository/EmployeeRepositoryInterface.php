<?php

declare(strict_types=1);

namespace App\Employee\Domain\Repository;

use App\Employee\Domain\Employee;
use Ramsey\Uuid\UuidInterface;

interface EmployeeRepositoryInterface
{
    public function get(UuidInterface $uuid): Employee;
    public function store(Employee $employee): void;
}
