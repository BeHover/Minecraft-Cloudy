<?php

namespace App\Entity\Authme;

/**
 * @ORM\Table(name="lands")
 * @ORM\Entity(repositoryClass=App\Repository\Authme\LandsRepository::class)
 */
class Lands
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
    private ?string $world;

    /**
     * @ORM\Column(type="string", length=36)
     */
    private ?string $nuid;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $type;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=16777215)
     */
    private ?string $area;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $members;

    /**
     * @ORM\Column(type="bigint")
     */
    private ?string $created;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $balance;

    /**
     * @ORM\Column(type="string", length=300)
     */
    private ?string $title;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $spawn;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $inbox;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $vs;

    /**
     * @ORM\Column(type="bigint")
     */
    private ?string $shield;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $stats;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function getMembers(): ?string
    {
        return $this->members;
    }

    public function getCreated(): ?string
    {
        return $this->created;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function getSpawn(): ?string
    {
        return $this->spawn;
    }

    public function getVs(): ?string
    {
        return $this->vs;
    }

    public function getShield(): ?string
    {
        return $this->shield;
    }

    public function getStats(): ?string
    {
        return $this->stats;
    }
}
