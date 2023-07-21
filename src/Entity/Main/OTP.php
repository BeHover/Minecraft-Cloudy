<?php

namespace App\Entity\Main;

use DateTimeImmutable;
use DateTimeInterface;

use App\Repository\Main\OTPRepository;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(name: "otp")]
#[ORM\Entity(repositoryClass: OTPRepository::class)]
class OTP
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    private UuidInterface $id;

    #[ORM\Column(type: "integer")]
    private ?int $otp;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private User $user;

    #[ORM\Column(name: "created_at", type: "datetime", nullable: false)]
    private ?DateTimeInterface $createdAt;

    public function __construct(
        int $otp,
        User $user
    )
    {
        $this->id = Uuid::uuid6();
        $this->otp = $otp;
        $this->user = $user;

        try {
            $this->createdAt = new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga"));
        } catch (\Exception $e) {
        }
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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
