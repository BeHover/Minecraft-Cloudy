<?php

declare(strict_types=1);

namespace App\Repository\Main;

use App\Entity\Main\OTP;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OTP|null find($id, $lockMode = null, $lockVersion = null)
 * @method OTP|null findOneBy(array $criteria, array $orderBy = null)
 * @method OTP[]    findAll()
 * @method OTP[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OTPRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OTP::class);
    }

    public function save(OTP $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OTP $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
