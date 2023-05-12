<?php

declare(strict_types=1);

namespace Tests\App\Delegation\Domain\Calculator;

use App\Delegation\Domain\Calculator\Calculator;
use App\Delegation\Domain\ValueObject\Country;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function testGetTotalAllowanceReturnsExpectedValue()
    {
        $country = new Country('DE');

        // Monday to Sunday
        $startDate = new \DateTimeImmutable('2023-05-01 14:00:00');
        $endDate = new \DateTimeImmutable('2023-05-07 14:00:00');
        $calculator = new Calculator($startDate, $endDate, $country);
        $this->assertEquals(250, $calculator->getTotalAllowance());

        // Saturday to Sunday
        $startDate = new \DateTimeImmutable('2023-05-06 14:00:00');
        $endDate = new \DateTimeImmutable('2023-05-07 14:00:00');
        $calculator = new Calculator($startDate, $endDate, $country);
        $this->assertEquals(0, $calculator->getTotalAllowance());

        // Monday to next Monday with double allowance
        $startDate = new \DateTimeImmutable('2023-05-01 14:00:00');
        $endDate = new \DateTimeImmutable('2023-05-08 14:00:00');
        $calculator = new Calculator($startDate, $endDate, $country);
        $this->assertEquals(350, $calculator->getTotalAllowance());

        // Monday to Sunday (starts after 16:00)
        $startDate = new \DateTimeImmutable('2023-05-01 16:01:00');
        $endDate = new \DateTimeImmutable('2023-05-07 14:00:00');
        $calculator = new Calculator($startDate, $endDate, $country);
        $this->assertEquals(200, $calculator->getTotalAllowance());

        // Monday to next Monday with double allowance (starts after 16:00)
        $startDate = new \DateTimeImmutable('2023-05-01 16:01:00');
        $endDate = new \DateTimeImmutable('2023-05-08 14:00:00');
        $calculator = new Calculator($startDate, $endDate, $country);
        $this->assertEquals(300, $calculator->getTotalAllowance());

        // Monday to Friday (ends before 08:00)
        $startDate = new \DateTimeImmutable('2023-05-01 14:00:00');
        $endDate = new \DateTimeImmutable('2023-05-05 07:59:00');
        $calculator = new Calculator($startDate, $endDate, $country);
        $this->assertEquals(200, $calculator->getTotalAllowance());
    }
}
