<?php

namespace App\Entity\Authme;

use App\Repository\Authme\LuckPermsPlayersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="luckperms_players")
 * @ORM\Entity(repositoryClass=App\Repository\Authme\LuckPermsPlayersRepository::class)
 */
class LuckPermsPlayers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="string", length=36)
     */
    private ?string $uuid;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private string $username;

    /**
     * @ORM\Column(name="primary_group", type="string", length=36)
     */
    private string $primaryGroup;

    public function getUUID(): ?string
    {
        return $this->uuid;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPrimaryGroup(): ?string
    {
        return $this->primaryGroup;
    }
}