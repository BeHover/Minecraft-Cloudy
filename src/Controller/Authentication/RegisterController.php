<?php

declare(strict_types=1);

namespace App\Controller\Authentication;

use App\Service\Authentication\AuthenticationManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/users")]
class RegisterController extends AbstractController
{
    public function __construct(
        private readonly AuthenticationManagementService $authenticationService
    ) {
    }

    #[Route("/register", name: "user_register", methods: ["POST"])]
    public function register(
        Request $plainRequest
    ): JsonResponse
    {
        $requestData = $plainRequest->toArray();
        $username = $requestData["username"];
        $password = $requestData["password"];
        $email = $requestData["email"];
        $locale = $plainRequest->query->get("locale", "en_EN");

        return $this->authenticationService->register($username, $password, $email, $locale);
    }
}