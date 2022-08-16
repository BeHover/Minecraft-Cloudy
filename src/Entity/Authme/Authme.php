<?php

namespace App\Entity\Authme;

/**
 * @ORM\Table(name="players")
 * @ORM\Entity(repositoryClass=App\Repository\Authme\AuthmeRepository::class)
 */
class Authme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $realname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $password;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private ?string $ip;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private ?string $lastlogin;

    /**
     * @ORM\Column(type="float")
     */
    private int $x = 0;

    /**
     * @ORM\Column(type="float")
     */
    private int $y = 0;

    /**
     * @ORM\Column(type="float")
     */
    private int $z = 0;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $world = 'worlds';

    /**
     * @ORM\Column(type="bigint")
     */
    private ?int $regdate;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private ?string $regip;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $yaw;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $pitch;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="smallint")
     */
    private int $isLogged = 0;

    /**
     * @ORM\Column(type="smallint")
     */
    private int $hasSession = 0;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private ?string $totp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRealname(): ?string
    {
        return $this->realname;
    }

    public function setRealname(string $realname): self
    {
        $this->realname = $realname;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function getLastlogin(): ?string
    {
        return $this->lastlogin;
    }

    public function getX(): ?float
    {
        return $this->x;
    }

    public function getY(): ?float
    {
        return $this->y;
    }

    public function getZ(): ?float
    {
        return $this->z;
    }

    public function getWorld(): ?string
    {
        return $this->world;
    }

    public function getRegdate(): ?int
    {
        return $this->regdate;
    }

    public function setRegdate(int $regdate): self
    {
        $this->regdate = $regdate;

        return $this;
    }

    public function getRegip(): ?string
    {
        return $this->regip;
    }

    public function setRegip(?string $regip): self
    {
        $this->regip = $regip;

        return $this;
    }

    public function getYaw(): ?float
    {
        return $this->yaw;
    }

    public function getPitch(): ?float
    {
        return $this->pitch;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIsLogged(): ?int
    {
        return $this->isLogged;
    }

    public function getHasSession(): ?int
    {
        return $this->hasSession;
    }
}
