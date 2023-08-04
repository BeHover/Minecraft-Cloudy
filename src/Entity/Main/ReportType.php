<?php

namespace App\Entity\Main;

use App\Repository\Main\ReportTypeRepository;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(name: "report_type")]
#[ORM\Entity(repositoryClass: ReportTypeRepository::class)]
class ReportType
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    private UuidInterface $id;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private ?string $name;

    #[ORM\Column(name: "created_at", type: "datetime", nullable: false)]
    private ?DateTimeInterface $createdAt;

    public function __construct(
        string $name
    )
    {
        $this->id = Uuid::uuid6();
        $this->name = $name;

        try {
            $this->createdAt = new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga"));
        } catch (Exception $e) {
        }
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }
}
