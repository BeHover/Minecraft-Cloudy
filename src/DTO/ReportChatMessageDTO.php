<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Main\Report;
use App\Entity\Main\User;
use DateTimeInterface;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class ReportChatMessageDTO implements JsonSerializable
{
    public function __construct(
        private readonly UuidInterface $id,
        private readonly Report $report,
        private readonly User $user,
        private readonly string $text,
        private readonly DateTimeInterface $createdAt
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            "message" => $this->text,
            "created" => [
                "user" => [
                    "uuid" => $this->user->getId(),
                    "username" => $this->user->getUsername()
                ],
                "datetime" => $this->createdAt->format("d-m-Y, H:i:s")
            ]
        ];
    }
}