<?php

namespace App\Entity\Authme;

use App\Repository\Authme\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="players")
 * @ORM\Entity(repositoryClass=App\Repository\Authme\PlayerRepository::class)
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=36)
     */
    private ?string $player_uuid;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private ?string $player_name;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $money;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $offline_money;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private ?string $sync_complete;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private ?string $last_seen;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerUUID(): ?string
    {
        return $this->player_uuid;
    }

    public function getPlayerName(): ?string
    {
        return $this->player_name;
    }

    public function getMoney(): ?float
    {
        return $this->money;
    }

    public function getOfflineMoney(): ?float
    {
        return $this->offline_money;
    }

    public function getLastSeen(): ?string
    {
        return $this->last_seen;
    }
}
