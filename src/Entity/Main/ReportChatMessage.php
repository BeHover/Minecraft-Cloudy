<?php

namespace App\Entity\Main;

use App\Repository\Main\ReportChatMessageRepository;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(name: "report_chat_messages")]
#[ORM\Entity(repositoryClass: ReportChatMessageRepository::class)]
class ReportChatMessage
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    private UuidInterface $id;

    #[ORM\ManyToOne(targetEntity: Report::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Report $report = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(type: "text", nullable: false)]
    private ?string $text;

    #[ORM\Column(name: "created_at", type: "datetime", nullable: false)]
    private ?DateTimeInterface $createdAt;

    public function __construct(
        Report $report,
        User $user,
        string $text
    )
    {
        $this->id = Uuid::uuid6();
        $this->report = $report;
        $this->user = $user;
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

    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(?Report $report): self
    {
        $this->report = $report;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
