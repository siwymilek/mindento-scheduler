<?php

declare(strict_types=1);

namespace App\Employee\Infrastructure\Repository;

use App\Employee\Domain\Employee;
use App\Employee\Domain\Repository\EmployeeRepositoryInterface;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Ramsey\Uuid\UuidInterface;

final class EmployeeStore extends EventSourcingRepository implements EmployeeRepositoryInterface
{
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = []
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            Employee::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }

    public function store(Employee $employee): void
    {
        $this->save($employee);
    }

    public function get(UuidInterface $uuid): Employee
    {
        /** @var Employee $employee */
        $employee = $this->load($uuid->toString());
        return $employee;
    }
}
