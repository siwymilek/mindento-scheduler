<?php

declare(strict_types=1);

namespace App\Delegation\Infrastructure\Repository;

use App\Delegation\Domain\Delegation;
use App\Delegation\Domain\Repository\DelegationRepositoryInterface;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Ramsey\Uuid\UuidInterface;

final class DelegationStore extends EventSourcingRepository implements DelegationRepositoryInterface
{
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = []
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            Delegation::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }

    public function store(Delegation $delegation): void
    {
        $this->save($delegation);
    }

    public function get(UuidInterface $uuid): Delegation
    {
        /** @var Delegation $delegation */
        $delegation = $this->load($uuid->toString());
        return $delegation;
    }
}
