<?php

declare(strict_types=1);

namespace App\Delegation\Application\Command\CreateDelegation;

use App\Delegation\Domain\Delegation;
use App\Delegation\Domain\Repository\DelegationRepositoryInterface;
use App\Delegation\Domain\Specification\DelegationOverlapsWithAnotherSpecificationInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\DateTimeException;

final class CreateDelegationCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly DelegationRepositoryInterface $delegationRepositoryInterface,
        private readonly DelegationOverlapsWithAnotherSpecificationInterface $delegationOverlapsWithAnotherSpecification,
    ) {
    }

    /**
     * @throws DateTimeException
     */
    public function __invoke(CreateDelegationCommand $command): void
    {
        $delegation = Delegation::create(
            $command->uuid,
            $command->employeeUuid,
            $command->period,
            $command->country,
            $this->delegationOverlapsWithAnotherSpecification,
        );

        $this->delegationRepositoryInterface->store($delegation);
    }
}
