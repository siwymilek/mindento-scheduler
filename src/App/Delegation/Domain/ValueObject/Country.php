<?php

declare(strict_types=1);

namespace App\Delegation\Domain\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class Country implements \JsonSerializable
{
    private static array $list = [
        'PL' => ['dailyAllowance' => 10, 'currency' => 'PLN'],
        'DE' => ['dailyAllowance' => 50, 'currency' => 'PLN'],
        'GB' => ['dailyAllowance' => 75, 'currency' => 'PLN'],
    ];

    public function __construct(public string $country)
    {
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromString(string $country): self
    {
        Assertion::inArray($country, array_keys(self::$list), 'Country is unavailable.');
        return new self($country);
    }

    public function toString(): string
    {
        return $this->country;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function getDailyAllowance(): int
    {
        return self::$list[$this->country]['dailyAllowance'];
    }

    public function getCurrency(): string
    {
        return self::$list[$this->country]['currency'];
    }

    public function jsonSerialize(): string
    {
        return $this->toString();
    }
}
