<?php
declare(strict_types=1);

namespace Tests\App\Delegation\Domain\ValueObject;

use App\Delegation\Domain\ValueObject\Country;
use PHPUnit\Framework\TestCase;

class CountryTest extends TestCase
{
    public function testFromStringReturnsCountryObject()
    {
        $country = Country::fromString('PL');
        $this->assertInstanceOf(Country::class, $country);
    }

    public function testFromStringThrowsExceptionForInvalidCountry()
    {
        $this->expectException(\InvalidArgumentException::class);
        Country::fromString('INVALID');
    }

    public function testToStringReturnsString()
    {
        $country = new Country('PL');
        $this->assertEquals('PL', $country->toString());
    }

    public function testGetDailyAllowanceReturnsInt()
    {
        $country = new Country('PL');
        $this->assertIsInt($country->getDailyAllowance());
    }

    public function testGetCurrencyReturnsString()
    {
        $country = new Country('PL');
        $this->assertIsString($country->getCurrency());
    }

    public function testJsonSerializeReturnsString()
    {
        $country = new Country('PL');
        $this->assertIsString(json_encode($country));
    }
}
