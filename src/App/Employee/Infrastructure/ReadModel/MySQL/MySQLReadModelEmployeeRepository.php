<?php
declare(strict_types=1);

namespace App\Employee\Infrastructure\ReadModel\MySQL;

use App\Employee\Infrastructure\ReadModel\EmployeeReadModel;
use App\Shared\Infrastructure\Persistence\ReadModel\Exception\NotFoundException;
use App\Shared\Infrastructure\Persistence\ReadModel\Repository\MySQLRepository;
use Doctrine\ORM\NonUniqueResultException;
use Ramsey\Uuid\UuidInterface;

final class MySQLReadModelEmployeeRepository extends MySQLRepository
{
    protected function setEntityManager(): void
    {
        $objectRepository = $this->entityManager->getRepository(EmployeeReadModel::class);
        $this->repository = $objectRepository;
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function oneByUuid(UuidInterface $uuid): EmployeeReadModel
    {
        $qb = $this->repository
            ->createQueryBuilder('employee')
            ->where('employee.uuid = :uuid')
            ->setParameter('uuid', $uuid->getBytes())
        ;

        return $this->oneOrException($qb);
    }

    public function add(EmployeeReadModel $employeeReadModel): void
    {
        $this->register($employeeReadModel);
    }
}
