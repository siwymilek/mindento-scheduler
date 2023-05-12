<?php

declare(strict_types=1);

namespace App\Delegation\Domain\ValueObject;

use App\Delegation\Domain\Exception\InvalidPeriodException;
use App\Shared\Domain\ValueObject\DateTime;

final class Period
{
    /**
     * @throws InvalidPeriodException
     */
    public function __construct(
        private readonly DateTime $startDate,
        private readonly DateTime $endDate,
    ) {
        if ($this->startDate > $this->endDate) {
            throw new InvalidPeriodException('Start date must be before end date.');
        }
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function overlapsWith(Period $other): bool
    {
        return $this->startDate <= $other->endDate && $other->startDate <= $this->endDate;
    }
}
