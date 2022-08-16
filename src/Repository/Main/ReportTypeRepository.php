<?php

namespace App\Repository\Main;

use App\Entity\Main\ReportType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportType[]    findAll()
 * @method ReportType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportType::class);
    }
}
