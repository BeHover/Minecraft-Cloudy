<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Main\ReportType;
use App\Entity\Main\User;
use DateTimeInterface;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class ReportDTO implements JsonSerializable
{
    public function __construct(
        private readonly UuidInterface $id,
        private readonly User $createdBy,
        private readonly ReportType $type,
        private readonly string $text,
        private readonly DateTimeInterface $createdAt,
        private readonly ?DateTimeInterface $closedAt,
        private readonly ?User $closedBy
    ) {
    }

    public function jsonSerialize(): array
    {
        $report = [
            "uuid" => $this->id,
            "type" => [
                "uuid" => $this->type->getId(),
                "username" => $this->type->getName()
            ],
            "text" => $this->text,
            "created" => [
                "user" => [
                    "uuid" => $this->createdBy->getId(),
                    "username" => $this->createdBy->getUsername()
                ],
                "datetime" => $this->createdAt->format("d-m-Y, H:i:s")
            ]
        ];

        if ($this->closedBy !== null && $this->closedAt !== null) {
            $report["closed"] = [
                "user" => [
                    "uuid" => $this->closedBy->getId(),
                    "username" => $this->closedBy->getUsername()
                ],
                "datetime" => $this->closedAt->format("d-m-Y, H:i:s")
            ];
        }

        return $report;
    }
}