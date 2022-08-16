<?php

namespace App\Entity\Authme;

use App\Repository\Authme\AuthmeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="authme")
 * @ORM\Entity(repositoryClass=App\Repository\Authme\AuthmeRepository::class)
 */
class Authme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $realname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $ip;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $lastlogin;

    /**
     * @ORM\Column(type="float")
     */
    private $x = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $y = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $z = 0;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $world = 'worlds';

    /**
     * @ORM\Column(type="bigint")
     */
    private $regdate;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $regip;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $yaw;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pitch;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="smallint")
     */
    private $isLogged = 0;

    /**
     * @ORM\Column(type="smallint")
     */
    private $hasSession = 0;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $totp;

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

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getLastlogin(): ?string
    {
        return $this->lastlogin;
    }

    public function setLastlogin(?string $lastlogin): self
    {
        $this->lastlogin = $lastlogin;

        return $this;
    }

    public function getX(): ?float
    {
        return $this->x;
    }

    public function setX(?float $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?float
    {
        return $this->y;
    }

    public function setY(?float $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function getZ(): ?float
    {
        return $this->z;
    }

    public function setZ(?float $z): self
    {
        $this->z = $z;

        return $this;
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

    public function setYaw(?float $yaw): self
    {
        $this->yaw = $yaw;

        return $this;
    }

    public function getPitch(): ?float
    {
        return $this->pitch;
    }

    public function setPitch(?float $pitch): self
    {
        $this->pitch = $pitch;

        return $this;
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

    public function setIsLogged(int $isLogged): self
    {
        $this->isLogged = $isLogged;

        return $this;
    }

    public function getHasSession(): ?int
    {
        return $this->hasSession;
    }

    public function setHasSession(int $hasSession): self
    {
        $this->hasSession = $hasSession;

        return $this;
    }

    public function getTotp(): ?string
    {
        return $this->totp;
    }

    public function setTotp(string $totp): self
    {
        $this->totp = $totp;

        return $this;
    }
}
