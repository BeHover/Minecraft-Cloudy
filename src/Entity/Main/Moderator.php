<?php

namespace App\Entity\Main;

use DateTimeInterface;

use App\Repository\Main\ModeratorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "moderator")]
#[ORM\Entity(repositoryClass: ModeratorRepository::class)]
class Moderator
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?User $user;

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

    public function getId(): ?int
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
