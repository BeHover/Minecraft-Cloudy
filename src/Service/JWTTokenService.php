<?php

declare(strict_types=1);

namespace App\Service;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTTokenService
{
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function createToken(UserInterface $user): string
    {
        return $this->jwtManager->create($user);
    }

    /**
     * @throws JWTDecodeFailureException
     */
    public function decodeToken(PreAuthenticationJWTUserToken $token): bool|array
    {
        return $this->jwtManager->decode($token);
    }
}