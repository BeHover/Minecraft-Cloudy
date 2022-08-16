<?php

namespace App\Entity\Authme;

use App\Repository\Authme\LandsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="lands")
 * @ORM\Entity(repositoryClass=LandsRepository::class)
 */
class Lands
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=36)
     */
    private $world;

    /**
     * @ORM\Column(type="string", length=36)
     */
    private $nuid;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=16777215)
     */
    private $area;

    /**
     * @ORM\Column(type="text")
     */
    private $members;

    /**
     * @ORM\Column(type="bigint")
     */
    private $created;

    /**
     * @ORM\Column(type="float")
     */
    private $balance;

    /**
     * @ORM\Column(type="string", length=300)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $spawn;

    /**
     * @ORM\Column(type="text")
     */
    private $inbox;

    /**
     * @ORM\Column(type="text")
     */
    private $vs;

    /**
     * @ORM\Column(type="bigint")
     */
    private $shield;

    /**
     * @ORM\Column(type="text")
     */
    private $stats;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorld(): ?string
    {
        return $this->world;
    }

    public function setWorld(string $world): self
    {
        $this->world = $world;

        return $this;
    }

    public function getNuid(): ?string
    {
        return $this->nuid;
    }

    public function setNuid(string $nuid): self
    {
        $this->nuid = $nuid;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function setArea(string $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getMembers(): ?string
    {
        return $this->members;
    }

    public function setMembers(string $members): self
    {
        $this->members = $members;

        return $this;
    }

    public function getCreated(): ?string
    {
        return $this->created;
    }

    public function setCreated(string $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSpawn(): ?string
    {
        return $this->spawn;
    }

    public function setSpawn(string $spawn): self
    {
        $this->spawn = $spawn;

        return $this;
    }

    public function getInbox(): ?string
    {
        return $this->inbox;
    }

    public function setInbox(string $inbox): self
    {
        $this->inbox = $inbox;

        return $this;
    }

    public function getVs(): ?string
    {
        return $this->vs;
    }

    public function setVs(string $vs): self
    {
        $this->vs = $vs;

        return $this;
    }

    public function getShield(): ?string
    {
        return $this->shield;
    }

    public function setShield(string $shield): self
    {
        $this->shield = $shield;

        return $this;
    }

    public function getStats(): ?string
    {
        return $this->stats;
    }

    public function setStats(string $stats): self
    {
        $this->stats = $stats;

        return $this;
    }
}
