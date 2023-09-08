<?php

declare(strict_types=1);

namespace App\DTO\ReportType;

use DateTimeInterface;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class ReportTypeDTO implements JsonSerializable
{

    public function __construct(
        private readonly UuidInterface $id,
        private readonly string $name,
        private readonly DateTimeInterface $createdAt
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            "uuid" => $this->id,
            "name" => $this->name,
            "created" => $this->createdAt->format("d-m-Y, H:i")
        ];
    }
}