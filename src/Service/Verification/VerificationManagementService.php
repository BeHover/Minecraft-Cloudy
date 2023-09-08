<?php

declare(strict_types=1);

namespace App\Service\Verification;

use App\Entity\Authme\Authme;
use App\Entity\Main\User;
use App\Repository\Authme\AuthmeRepository;
use App\Repository\Main\OTPRepository;
use App\Repository\Main\UserRepository;
use App\Security\EmailVerifier;
use App\Service\OTP\OTPService;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class VerificationManagementService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly AuthmeRepository $authmeRepository,
        private readonly OTPRepository $OTPRepository,
        private readonly OTPService $OTPService,
        private readonly TranslatorInterface $translator,
        private readonly EmailVerifier $emailVerifier
    ) {
    }

    public function verify(User $user, ?string $otp, string $locale): JsonResponse
    {
        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!$otp || !preg_match("/^\d{6}$/", $otp)) {
            $message = $this->translator->trans("user.otp.invalid", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_NOT_FOUND);
        }

        $cache = $this->OTPRepository->findOneBy(["user" => $user]);

        if ($cache === null) {
            $message = $this->translator->trans("user.otp.not_found", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_NOT_FOUND);
        }

        if ((int) $otp !== $cache->getOTP()) {
            $message = $this->translator->trans("user.otp.invalid", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_NOT_FOUND);
        }

        $this->OTPRepository->remove($cache, true);

        $user->setIsVerified(true);
        $this->userRepository->save($user, true);

        if (null === $this->authmeRepository->findOneBy(["realname" => $user->getUsername()])) {
            $authmeUser = new Authme($user->getUsername(), $user->getPassword());
            $this->authmeRepository->save($authmeUser, true);

            $message = $this->translator->trans("user.otp.confirm.email_with_authme", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_CREATED);
        }

        $message = $this->translator->trans("user.otp.confirm.email", locale: $locale);
        return new JsonResponse(["message" => $message]);
    }

    public function sendOTP(User $user, string $locale): JsonResponse
    {
        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $code = $this->OTPService->generateOTP($user);
        } catch (Exception $e) {
            $message = $this->translator->trans("user.otp.generate.failed", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->emailVerifier->sendEmailConfirmation($user,
            (new TemplatedEmail())
                ->to($user->getEmail())
                ->subject("Подтверждение почты")
                ->htmlTemplate("email/confirmation_email.html.twig"),
            $code
        );

        $message = $this->translator->trans("user.otp.generate.successfully", locale: $locale);
        return new JsonResponse(["message" => $message]);
    }
}