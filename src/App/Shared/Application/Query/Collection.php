<?php

declare(strict_types=1);

namespace App\Shared\Application\Query;

class Collection
{
    public function __construct(
        public readonly array $data
    ) {
    }
}
