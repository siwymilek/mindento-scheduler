<?php

declare(strict_types=1);

namespace App\Delegation\Domain\Repository;

use App\Delegation\Domain\Delegation;
use Ramsey\Uuid\UuidInterface;

interface DelegationRepositoryInterface
{
    public function get(UuidInterface $uuid): Delegation;
    public function store(Delegation $delegation): void;
}
