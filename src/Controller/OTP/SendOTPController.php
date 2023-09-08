<?php

declare(strict_types=1);

namespace App\Controller\OTP;

use App\Entity\Main\User;
use App\Service\Verification\VerificationManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/users")]
class SendOTPController extends AbstractController
{
    public function __construct(
        private readonly VerificationManagementService $verificationService
    ) {
    }

    #[Route("/verify/send", name: "send_otp", methods: ["GET"])]
    public function sendOTP(
        Request $plainRequest
    ): JsonResponse
    {
        $locale = $plainRequest->query->get("locale", "en_EN");

        /** @var User $user */
        $user = $this->getUser();

        return $this->verificationService->sendOTP($user, $locale);
    }
}