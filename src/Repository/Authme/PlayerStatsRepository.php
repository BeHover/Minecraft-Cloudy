<?php

namespace App\Repository\Authme;

use App\Entity\Authme\PlayerStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlayerStats|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerStats|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerStats[]    findAll()
 * @method PlayerStats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerStatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerStats::class);
    }

    // /**
    //  * @return PlayerStats[] Returns an array of PlayerStats objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlayerStats
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
