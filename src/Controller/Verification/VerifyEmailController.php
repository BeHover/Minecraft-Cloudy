<?php

declare(strict_types=1);

namespace App\Controller\Verification;

use App\Entity\Main\User;
use App\Service\Verification\VerificationManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/users")]
class VerifyEmailController extends AbstractController
{
    public function __construct(
        private readonly VerificationManagementService $verificationService
    ) {
    }

    #[Route("/verify", name: "user_verify", methods: ["POST"])]
    public function verify(
        Request $plainRequest
    ): JsonResponse
    {
        $requestData = $plainRequest->toArray();
        $otp = $requestData["otp"];
        $locale = $plainRequest->query->get("locale", "en_EN");

        /** @var User $user */
        $user = $this->getUser();

        return $this->verificationService->verify($user, $otp, $locale);
    }
}