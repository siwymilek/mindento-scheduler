<?php

declare(strict_types=1);

namespace App\Employee\Infrastructure\ReadModel;

use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use Broadway\ReadModel\SerializableReadModel;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class EmployeeReadModel implements SerializableReadModel
{
    final public const TYPE = 'EmployeeReadModel';

    private function __construct(private readonly UuidInterface $uuid, public DateTime $createdAt, public ?\App\Shared\Domain\ValueObject\DateTime $updatedAt)
    {
    }

    /**
     * @throws DateTimeException
     */
    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }

    /**
     * @throws DateTimeException
     */
    public static function deserialize(array $data): self
    {
        return new self(
            Uuid::fromString($data['uuid']),
            DateTime::fromString($data['created_at']),
            isset($data['updated_at']) ? DateTime::fromString($data['updated_at']) : null
        );
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->getId(),
        ];
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function changeUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->uuid->toString();
    }
}
