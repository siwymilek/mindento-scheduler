<?php
declare(strict_types=1);

namespace App\Delegation\Domain\Calculator;

use App\Delegation\Domain\ValueObject\Country;

class Calculator implements CalculatorInterface
{
    private const PAYDAY_MARGIN = 8 * 60 * 60;

    public function __construct(
        private readonly \DateTimeImmutable $startDate,
        private readonly \DateTimeImmutable $endDate,
        private readonly Country $country,
    ) {
    }

    private function getDailyAllowance(\DateTimeImmutable $date): int
    {
        $dayOfWeek = (int) $date->format('w');

        // skip if delegation starts after 16:00 or ends before 08:00
        if (
            $date->format('Hi') !== '2359' && // do not count full days
            ((int) $date->format('Hi') > 1600 || (int) $date->format('Hi') < 800)
        ) {
            return 0;
        }

        // skip weekends
        if ($dayOfWeek === 0 || $dayOfWeek > 5) {
            return 0;
        }

        $diff = $date->setTime(23, 59, 59)->diff($this->startDate);
        // double allowance after the 7th day
        if ($diff->days >= 7 && ($date->getTimestamp() > ($this->startDate->getTimestamp() + self::PAYDAY_MARGIN))) {
            return $this->country->getDailyAllowance() * 2;
        }

        return $this->country->getDailyAllowance();
    }

    public function getTotalAllowance(): int
    {
        $allowance = 0;
        $dateInterval = new \DateInterval('P1D');
        $datePeriod = new \DatePeriod($this->startDate, $dateInterval, $this->endDate->modify('+1 day'));
        $iterations = iterator_count($datePeriod);

        foreach ($datePeriod as $i => $date) {
            if ($i === $iterations-1) {
                $date = $this->endDate;
            } else if ($i > 0) {
                $date = $date->setTime(23, 59, 59);
            }

            $allowance += $this->getDailyAllowance($date);
        }

        return $allowance;
    }
}