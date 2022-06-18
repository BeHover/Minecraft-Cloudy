<?php

namespace App\Entity\Authme;

use App\Repository\Authme\PlayerStatsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="superstats")
 * @ORM\Entity(repositoryClass=PlayerStatsRepository::class)
 */
class PlayerStats
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $data;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $server;

    /**
     * @ORM\Column(type="decimal", precision=22, scale=2)
     */
    private $value;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $stringvalue;

    /**
     * @ORM\Column(type="bigint")
     */
    private $time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(string $data): self
    {
        $this->data = $data;

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

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getServer(): ?string
    {
        return $this->server;
    }

    public function setServer(string $server): self
    {
        $this->server = $server;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getStringvalue(): ?string
    {
        return $this->stringvalue;
    }

    public function setStringvalue(string $stringvalue): self
    {
        $this->stringvalue = $stringvalue;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }
}
