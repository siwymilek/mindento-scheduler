<?php
declare(strict_types=1);

namespace App\Delegation\Infrastructure\ReadModel\MySQL;

use App\Delegation\Domain\Repository\GetDelegationsInPeriodRepositoryInterface;
use App\Delegation\Domain\ValueObject\Period;
use App\Delegation\Infrastructure\ReadModel\DelegationReadModel;
use App\Shared\Infrastructure\Persistence\ReadModel\Exception\NotFoundException;
use App\Shared\Infrastructure\Persistence\ReadModel\Repository\MySQLRepository;
use Doctrine\ORM\NonUniqueResultException;
use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Query\Expr\Andx;

final class MySQLReadModelDelegationRepository extends MySQLRepository implements GetDelegationsInPeriodRepositoryInterface
{
    protected function setEntityManager(): void
    {
        $objectRepository = $this->entityManager->getRepository(DelegationReadModel::class);
        $this->repository = $objectRepository;
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function oneByUuid(UuidInterface $uuid): DelegationReadModel
    {
        $qb = $this->repository
            ->createQueryBuilder('delegation')
            ->where('delegation.uuid = :uuid')
            ->setParameter('uuid', $uuid->getBytes())
        ;

        return $this->oneOrException($qb);
    }

    public function getDelegationsByEmployeeUuid(UuidInterface $employeeUuid): array
    {
        $qb = $this->repository
            ->createQueryBuilder('delegation')
            ->where('delegation.employeeUuid = :uuid')
            ->orderBy('delegation.period.startDate', 'ASC')
            ->setParameter('uuid', $employeeUuid->getBytes())
        ;

        return $qb->getQuery()->getResult();
    }

    public function getDelegationsInPeriod(UuidInterface $employeeUuid, Period $period): array
    {
        $qb = $this->repository->createQueryBuilder('delegation');

        $qb
            ->where('delegation.employeeUuid = :uuid')
            ->andWhere(
                (new Andx())
                    ->add($qb->expr()->lte('delegation.period.startDate', ':endDate'))
                    ->add($qb->expr()->gte('delegation.period.endDate', ':startDate'))
            )
            ->setParameter('startDate', $period->getStartDate())
            ->setParameter('endDate', $period->getEndDate())
            ->setParameter('uuid', $employeeUuid->getBytes())
        ;

        return $qb->getQuery()->getResult();
    }

    public function add(DelegationReadModel $delegationReadModel): void
    {
        $this->register($delegationReadModel);
    }
}
