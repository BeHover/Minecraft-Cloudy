<?php

declare(strict_types=1);

namespace App\Repository\Authme;

use App\Entity\Authme\Authme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Authme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Authme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Authme[]    findAll()
 * @method Authme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthmeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Authme::class);
    }

    public function save(Authme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Authme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
