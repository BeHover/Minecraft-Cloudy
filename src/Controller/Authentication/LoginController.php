<?php

declare(strict_types=1);

namespace App\Controller\Authentication;

use App\Service\Authentication\AuthenticationManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/users")]
class LoginController extends AbstractController
{
    public function __construct(
        private readonly AuthenticationManagementService $authenticationService
    ) {
    }

    #[Route("/login", name: "user_login", methods: ["POST"])]
    public function login(
        Request $plainRequest
    ): JsonResponse
    {
        $requestData = $plainRequest->toArray();
        $username = $requestData["username"];
        $password = $requestData["password"];
        $locale = $plainRequest->query->get("locale", "en_EN");

        return $this->authenticationService->login($username, $password, $locale);
    }
}