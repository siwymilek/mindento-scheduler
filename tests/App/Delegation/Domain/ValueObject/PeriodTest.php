<?php

declare(strict_types=1);

namespace Tests\App\Delegation\Domain\ValueObject;

use App\Delegation\Domain\Exception\InvalidPeriodException;
use App\Delegation\Domain\ValueObject\Period;
use App\Shared\Domain\ValueObject\DateTime;
use PHPUnit\Framework\TestCase;

class PeriodTest extends TestCase
{
    public function testCanBeCreatedWithValidDates()
    {
        $start = new DateTime('2023-01-01 20:00:00');
        $end = new DateTime('2023-02-01 16:00:00');
        $period = new Period($start, $end);
        $this->assertInstanceOf(Period::class, $period);
    }

    public function testThrowsExceptionIfStartDateAfterEndDate()
    {
        $start = new DateTime('2023-02-01 20:00:00');
        $end = new DateTime('2023-01-01 16:00:00');
        $this->expectException(InvalidPeriodException::class);
        new Period($start, $end);
    }

    public function testGetStartDateReturnsStartDate()
    {
        $start = new DateTime('2023-01-01 20:00:00');
        $end = new DateTime('2023-02-01 16:00:00');
        $period = new Period($start, $end);
        $this->assertEquals($start, $period->getStartDate());
    }

    public function testGetEndDateReturnsEndDate()
    {
        $start = new DateTime('2023-01-01 20:00:00');
        $end = new DateTime('2023-02-01 16:00:00');
        $period = new Period($start, $end);
        $this->assertEquals($end, $period->getEndDate());
    }

    public function testOverlapsWithReturnsTrueIfOverlapping()
    {
        $start1 = new DateTime('2023-01-01 20:00:00');
        $end1 = new DateTime('2023-02-01 16:00:00');
        $start2 = new DateTime('2023-01-15 20:00:00');
        $end2 = new DateTime('2023-03-01 16:00:00');
        $period1 = new Period($start1, $end1);
        $period2 = new Period($start2, $end2);
        $this->assertTrue($period1->overlapsWith($period2));
    }

    public function testOverlapsWithReturnsFalseIfNotOverlapping()
    {
        $start1 = new DateTime('2023-01-01 20:00:00');
        $end1 = new DateTime('2023-02-01 16:00:00');
        $start2 = new DateTime('2023-03-01 20:00:00');
        $end2 = new DateTime('2023-04-01 16:00:00');
        $period1 = new Period($start1, $end1);
        $period2 = new Period($start2, $end2);
        $this->assertFalse($period1->overlapsWith($period2));
    }
}
