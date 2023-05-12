<?php

declare(strict_types=1);

namespace App\Employee\Application\Command\CreateEmployee;

use App\Shared\Application\Command\CommandInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class CreateEmployeeCommand implements CommandInterface
{
    public UuidInterface $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = Uuid::fromString($uuid);
    }
}
