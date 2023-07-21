<?php

namespace App\Security;

use App\Service\JWTTokenService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TokenAuthenticator extends AbstractAuthenticator
{
    private readonly JWTTokenService $JWTTokenService;

    public function __construct(
        JWTTokenService $JWTTokenService
    ) {
        $this->JWTTokenService = $JWTTokenService;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('authorization');
    }

    public function authenticate(Request $request): Passport
    {
        if (!$request->headers->has('authorization')) {
            throw new AuthenticationException();
        }

        $authorizationHeader = (string) $request->headers->get('authorization');
        preg_match('/^Bearer\s(\S+)/i', $authorizationHeader, $matches);
        if (!$matches) {
            throw new CustomUserMessageAuthenticationException("Invalid authorization header: {$authorizationHeader}");
        }

        $token = $matches[1];
        $data = $this->JWTTokenService->decodeToken($token);

        return new SelfValidatingPassport(
            new UserBadge($data["username"])
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            "message" => "Требуется авторизация пользователя.",
        ], Response::HTTP_UNAUTHORIZED);
    }
}