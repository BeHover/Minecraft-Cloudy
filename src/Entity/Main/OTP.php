<?php

namespace App\Entity\Main;

use DateTimeInterface;

use App\Repository\Main\OTPRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "otp")]
#[ORM\Entity(repositoryClass: OTPRepository::class)]
class OTP
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id;

    #[ORM\Column(type: "integer")]
    private ?int $otp;

    #[ORM\Column(type: "string", length: 180)]
    private ?string $username;

    #[ORM\Column(name: "created_at", type: "datetime", nullable: false)]
    private ?DateTimeInterface $createdAt;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getOTP(): ?int
    {
        return $this->otp;
    }

    public function setOTP(int $otp): self
    {
        $this->otp = $otp;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
