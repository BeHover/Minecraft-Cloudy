<?php

declare(strict_types=1);

namespace App\Service\JWT;

use App\Entity\Main\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTTokenService
{
    public function __construct(
        private readonly string $jwtKey
    ) {
    }

    public function encodeToken(User $user): string
    {
        return JWT::encode([
            "username" => $user->getUsername(),
        ], $this->jwtKey, "HS256");
    }

    public function decodeToken(string $token): array
    {
        return (array) JWT::decode($token, new Key($this->jwtKey, "HS256"));
    }
}