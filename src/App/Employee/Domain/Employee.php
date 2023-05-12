<?php

declare(strict_types=1);

namespace App\Employee\Domain;

use App\Employee\Domain\Event\EmployeeWasCreated;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Ramsey\Uuid\UuidInterface;

class Employee extends EventSourcedAggregateRoot
{
    private UuidInterface $uuid;
    private ?DateTime $createdAt = null;
    private ?DateTime $updatedAt = null;

    /**
     * @throws DateTimeException
     */
    public static function create(
        UuidInterface $uuid,
    ): self {
        $employee = new self();
        $employee->apply(new EmployeeWasCreated($uuid, DateTime::now()));

        return $employee;
    }

    protected function applyEmployeeWasCreated(EmployeeWasCreated $event): void
    {
        $this->uuid = $event->uuid;
    }

    private function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    private function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function createdAt(): string
    {
        return $this->createdAt->toString();
    }

    public function updatedAt(): ?string
    {
        return isset($this->updatedAt) ? $this->updatedAt->toString() : null;
    }

    public function uuid(): string
    {
        return $this->uuid->toString();
    }

    public function getAggregateRootId(): string
    {
        return $this->uuid->toString();
    }
}
