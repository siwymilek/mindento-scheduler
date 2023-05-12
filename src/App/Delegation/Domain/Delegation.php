<?php

declare(strict_types=1);

namespace App\Delegation\Domain;

use App\Delegation\Domain\Event\DelegationWasCreated;
use App\Delegation\Domain\Specification\DelegationOverlapsWithAnotherSpecificationInterface;
use App\Delegation\Domain\ValueObject\Country;
use App\Delegation\Domain\ValueObject\Period;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Ramsey\Uuid\UuidInterface;

class Delegation extends EventSourcedAggregateRoot
{
    private UuidInterface $uuid;
    private UuidInterface $employeeUuid;
    private DateTime $start;
    private DateTime $end;
    private Country $country;
    private int $allowance;
    private ?DateTime $createdAt = null;

    /**
     * @throws DateTimeException
     */
    public static function create(
        UuidInterface $uuid,
        UuidInterface $employeeUuid,
        Period $period,
        Country $country,
        int $allowance,
        DelegationOverlapsWithAnotherSpecificationInterface $delegationOverlapsWithAnotherSpecification,
    ): self {
        $delegationOverlapsWithAnotherSpecification->isOverlapping($employeeUuid, $period);

        $delegation = new self();
        $delegation->apply(new DelegationWasCreated(
            $uuid,
            $employeeUuid,
            $period,
            $country,
            $allowance,
            DateTime::now(),
        ));

        return $delegation;
    }

    protected function applyDelegationWasCreated(DelegationWasCreated $event): void
    {
        $this->uuid = $event->uuid;

        $this->setEmployeeUuid($event->employeeUuid);
        $this->setStart($event->period->getStartDate());
        $this->setEnd($event->period->getEndDate());
        $this->setCountry($event->country);
        $this->setAllowance($event->allowance);
        $this->setCreatedAt($event->createdAt);
    }

    private function setStart(DateTime $start): void
    {
        $this->start = $start;
    }

    private function setEnd(DateTime $end): void
    {
        $this->end = $end;
    }

    private function setCountry(Country $country): void
    {
        $this->country = $country;
    }

    private function setAllowance(int $allowance): void
    {
        $this->allowance = $allowance;
    }

    private function setEmployeeUuid(UuidInterface $employeeUuid): void
    {
        $this->employeeUuid = $employeeUuid;
    }

    private function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function createdAt(): string
    {
        return $this->createdAt->toString();
    }

    public function start(): string
    {
        return $this->start->toString();
    }

    public function end(): string
    {
        return $this->end->toString();
    }

    public function country(): string
    {
        return $this->country->toString();
    }

    public function allowance(): int
    {
        return $this->allowance;
    }

    public function employeeUuid(): string
    {
        return $this->employeeUuid->toString();
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
