<?php

declare(strict_types=1);

namespace App\Delegation\Domain\Event;

use App\Delegation\Domain\ValueObject\Country;
use App\Delegation\Domain\ValueObject\Period;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class DelegationWasCreated implements Serializable
{
    public function __construct(
        public UuidInterface $uuid,
        public UuidInterface $employeeUuid,
        public Period $period,
        public Country $country,
        public int $allowance,
        public DateTime $createdAt,
    ) {
    }

    /**
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'employee_uuid');
        Assertion::keyExists($data, 'period');
        Assertion::keyExists($data, 'country');
        Assertion::keyExists($data, 'allowance');
        Assertion::keyExists($data, 'created_at');

        return new self(
            Uuid::fromString($data['uuid']),
            Uuid::fromString($data['employee_uuid']),
            new Period(
                DateTime::fromString($data['period']['startDate']),
                DateTime::fromString($data['period']['endDate']),
            ),
            Country::fromString($data['country']),
            $data['allowance'],
            DateTime::fromString($data['created_at']),
        );
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'employee_uuid' => $this->employeeUuid->toString(),
            'period' => [
                'startDate' => $this->period->getStartDate()->toString(),
                'endDate' => $this->period->getEndDate()->toString(),
            ],
            'country' => $this->country->toString(),
            'allowance' => $this->allowance,
            'created_at' => $this->createdAt->toString(),
        ];
    }
}
