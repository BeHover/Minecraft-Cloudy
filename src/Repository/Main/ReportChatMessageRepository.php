<?php

declare(strict_types=1);

namespace App\Repository\Main;

use App\Entity\Main\ReportChatMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportChatMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportChatMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportChatMessage[]    findAll()
 * @method ReportChatMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportChatMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportChatMessage::class);
    }

    public function save(ReportChatMessage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReportChatMessage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
