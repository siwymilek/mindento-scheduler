<?php

declare(strict_types=1);

namespace App\Delegation\Infrastructure\ReadModel;

use App\Delegation\Domain\ValueObject\Country;
use App\Delegation\Domain\ValueObject\Period;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use Assert\AssertionFailedException;
use Broadway\ReadModel\SerializableReadModel;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class DelegationReadModel implements SerializableReadModel
{
    final public const TYPE = 'DelegationReadModel';

    private function __construct(
        private readonly UuidInterface $uuid,
        private readonly UuidInterface $employeeUuid,
        private readonly Period $period,
        private readonly Country $country,
        private readonly int $allowance,
        private readonly DateTime $createdAt,
    ) {
    }

    /**
     * @throws DateTimeException
     * @throws AssertionFailedException
     */
    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }

    /**
     * @throws DateTimeException
     * @throws AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
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
            'uuid' => $this->getId(),
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

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getId(): string
    {
        return $this->uuid->toString();
    }

    public function getEmployeeUuid(): UuidInterface
    {
        return $this->employeeUuid;
    }

    #[Groups('default')]
    public function getStart(): DateTime
    {
        return $this->period->getStartDate();
    }

    #[Groups('default')]
    public function getEnd(): DateTime
    {
        return $this->period->getEndDate();
    }

    #[Groups('default')]
    public function getCountry(): Country
    {
        return $this->country;
    }

    #[Groups('default')]
    public function getCurrency(): string
    {
        return $this->country->getCurrency();
    }

    #[Groups('default')]
    #[SerializedName('amount_due')]
    public function getAllowance(): int
    {
        return $this->allowance;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
