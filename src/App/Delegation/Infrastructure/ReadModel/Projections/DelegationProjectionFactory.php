<?php

declare(strict_types=1);

namespace App\Delegation\Infrastructure\ReadModel\Projections;

use App\Delegation\Domain\Event\DelegationWasCreated;
use App\Delegation\Infrastructure\ReadModel\DelegationReadModel;
use App\Delegation\Infrastructure\ReadModel\MySQL\MySQLReadModelDelegationRepository;
use App\Shared\Domain\Exception\DateTimeException;
use Broadway\ReadModel\Projector;

final class DelegationProjectionFactory extends Projector
{
    public function __construct(private readonly MySQLReadModelDelegationRepository $repository)
    {
    }

    /**
     * @throws DateTimeException
     */
    protected function applyDelegationWasCreated(DelegationWasCreated $delegationWasCreated): void
    {
        $delegationReadModel = DelegationReadModel::fromSerializable($delegationWasCreated);
        $this->repository->add($delegationReadModel);
    }
}
