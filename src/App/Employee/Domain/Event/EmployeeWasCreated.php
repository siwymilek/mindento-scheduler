<?php

declare(strict_types=1);

namespace App\Employee\Domain\Event;

use App\Shared\Domain\ValueObject\DateTime;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class EmployeeWasCreated implements Serializable
{
    public function __construct(public UuidInterface $uuid, public DateTime $createdAt)
    {
    }

    /**
     * @throws \Exception
     * @throws AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');

        return new self(
            Uuid::fromString($data['uuid']),
            DateTime::fromString($data['created_at'])
        );
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'created_at' => $this->createdAt->toString(),
        ];
    }
}
