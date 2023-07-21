<?php

namespace App\Entity\Main;

use DateTimeImmutable;
use DateTimeInterface;

use App\Repository\Main\ModeratorRepository;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(name: "moderator")]
#[ORM\Entity(repositoryClass: ModeratorRepository::class)]
class Moderator
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    private UuidInterface $id;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private User $user;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $realname;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $location;

    #[ORM\Column(name: "birthday", type: "datetime", nullable: false)]
    private ?DateTimeInterface $birthday;

    #[ORM\Column(name: "created_at", type: "datetime", nullable: false)]
    private ?DateTimeInterface $createdAt;

    #[ORM\Column(name: "updated_at", type: "datetime", nullable: false)]
    private ?DateTimeInterface $updatedAt;

    public function __construct(
        User $user,
        string $realname,
        string $location,
        DateTimeInterface $birthday
    )
    {
        $this->id = Uuid::uuid6();
        $this->user = $user;
        $this->realname = $realname;
        $this->location = $location;
        $this->birthday = $birthday;

        try {
            $this->createdAt = new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga"));
            $this->updatedAt = new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga"));
        } catch (\Exception $e) {
        }
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getBirthday(): ?DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

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

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
