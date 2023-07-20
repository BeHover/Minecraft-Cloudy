<?php

declare(strict_types=1);

namespace App\Repository\Authme;

use App\Entity\Authme\LuckPermsPlayers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LuckPermsPlayers|null find($id, $lockMode = null, $lockVersion = null)
 * @method LuckPermsPlayers|null findOneBy(array $criteria, array $orderBy = null)
 * @method LuckPermsPlayers[]    findAll()
 * @method LuckPermsPlayers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LuckPermsPlayersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LuckPermsPlayers::class);
    }

    public function save(LuckPermsPlayers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LuckPermsPlayers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
