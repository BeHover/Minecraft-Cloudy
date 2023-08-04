<?php

namespace App\Entity\Main;

use App\Repository\Main\ReportRepository;
use DateTimeImmutable;
use DateTimeZone;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(name: "report")]
#[ORM\Entity(repositoryClass: ReportRepository::class)]
class Report
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    private UuidInterface $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "created_by", nullable: false)]
    private User $createdBy;

    #[ORM\ManyToOne(targetEntity: ReportType::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ReportType $type;

    #[ORM\Column(type: "text", nullable: false)]
    private string $text;

    #[ORM\Column(name: "created_at", type: "datetime", nullable: false)]
    private DateTimeInterface $createdAt;

    #[ORM\Column(name: "closed_at", type: "datetime", nullable: true)]
    private ?DateTimeInterface $closedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "closed_by", nullable: true)]
    private ?User $closedBy;

    public function __construct(
        User $user,
        ReportType $type,
        string $text
    )
    {
        $this->id = Uuid::uuid6();
        $this->createdBy = $user;
        $this->type = $type;
        $this->text = $text;

        try {
            $this->createdAt = new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga"));
        } catch (\Exception $e) {
        }
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function getType(): ?ReportType
    {
        return $this->type;
    }

    public function setType(?ReportType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getClosedAt(): ?DateTimeInterface
    {
        return $this->closedAt;
    }

    public function getClosedBy(): ?User
    {
        return $this->closedBy;
    }

    public function getClosed() : bool
    {
        return null !== $this->getClosedAt() || null !== $this->getClosedBy();
    }

    public function setClosed(?User $user): self
    {
        $this->closedBy = $user;

        try {
            $this->closedAt = new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga"));
        } catch (Exception $e) {
        }

        return $this;
    }
}
