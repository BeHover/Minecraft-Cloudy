<?php

namespace App\Repository\Authme;

use App\Entity\Authme\Lands;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lands|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lands|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lands[]    findAll()
 * @method Lands[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LandsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lands::class);
    }
}
