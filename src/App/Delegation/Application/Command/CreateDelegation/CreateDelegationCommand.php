<?php

declare(strict_types=1);

namespace App\Delegation\Application\Command\CreateDelegation;

use App\Delegation\Domain\ValueObject\Country;
use App\Delegation\Domain\ValueObject\Period;
use App\Shared\Application\Command\CommandInterface;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class CreateDelegationCommand implements CommandInterface
{
    public UuidInterface $uuid;
    public UuidInterface $employeeUuid;
    public Period $period;
    public Country $country;

    /**
     * @throws DateTimeException
     */
    public function __construct(
        string $uuid,
        string $employeeUuid,
        string $start,
        string $end,
        string $country,
    ) {
        $this->uuid = Uuid::fromString($uuid);
        $this->employeeUuid = Uuid::fromString($employeeUuid);
        $this->period = new Period(DateTime::fromString($start), DateTime::fromString($end));
        $this->country = new Country($country);
    }
}
