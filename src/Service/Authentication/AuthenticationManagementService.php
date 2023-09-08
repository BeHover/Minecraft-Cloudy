<?php

declare(strict_types=1);

namespace App\Service\Authentication;

use App\Entity\Main\User;
use App\Repository\Main\UserRepository;
use App\Security\EmailVerifier;
use App\Service\JWT\JWTTokenService;
use App\Service\OTP\OTPService;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AuthenticationManagementService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordEncoderInterface $passwordEncoder,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JWTTokenService $JWTTokenService,
        private readonly OTPService $OTPService,
        private readonly TranslatorInterface $translator,
        private readonly EmailVerifier $emailVerifier,
        private readonly Filesystem $fileSystem
    ) {
    }

    public function login(?string $username, ?string $password, string $locale) : JsonResponse
    {
        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        if (empty(trim($username)) || empty(trim($password))) {
            $message = $this->translator->trans("user.login.empty_fields", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }


        if (!preg_match("/^\w{4,20}$/", $username)) {
            $message = $this->translator->trans("user.login.username.invalid", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        if (!preg_match("/^\w{5,24}$/", $password)) {
            $message = $this->translator->trans("user.login.password.invalid", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepository->findOneBy(["username" => $username]);

        if (null === $user) {
            $message = $this->translator->trans("user.not_found", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_NOT_FOUND);
        }

        if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
            $message = $this->translator->trans("user.login.incorrect_password", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $token = $this->JWTTokenService->encodeToken($user);

        $data = [
            "token" => $token,
            "user" => [
                "username" => $user->getUserIdentifier(),
                "email" => $user->getEmail(),
                "verified" => $user->isVerified(),
                "created" => $user->getCreatedAt()->format("d-m-Y, H:i"),
                "roles" => $user->getRoles()
            ]
        ];

        return new JsonResponse($data);
    }

    public function register(?string $username, ?string $password, ?string $email, string $locale) : JsonResponse
    {
        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!$username || !$password || !$email) {
            $message = $this->translator->trans("user.register.empty_fields", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        if ($this->userRepository->findOneBy(["username" => $username]) !== null) {
            $message = $this->translator->trans("user.register.username.used", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        if ($this->userRepository->findOneBy(["email" => $email]) !== null) {
            $message = $this->translator->trans("user.register.email.used", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        if (!preg_match("/^\w{4,20}$/", $username)) {
            $message = $this->translator->trans("user.register.username.invalid", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = $this->translator->trans("user.register.email.invalid", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $user = new User($username, $email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $this->userRepository->save($user);

        $cwd = getcwd();
        $this->fileSystem->copy(
            "$cwd/default/skin.png",
            "$cwd/images/skins/$username.png"
        );

        $token = $this->JWTTokenService->encodeToken($user);

        try {
            $code = $this->OTPService->generateOTP($user);
        } catch (Exception $e) {
            $message = $this->translator->trans("user.otp.generate.failed", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->emailVerifier->sendEmailConfirmation($user,
            (new TemplatedEmail())
                ->to($email)
                ->subject("Подтверждение почты")
                ->htmlTemplate("email/confirmation_email.html.twig"),
            $code
        );

        $data = [
            "token" => $token,
            "user" => [
                "username" => $user->getUserIdentifier(),
                "email" => $user->getEmail(),
                "verified" => $user->isVerified(),
                "created" => $user->getCreatedAt()->format("d-m-Y, H:i"),
                "roles" => $user->getRoles()
            ]
        ];

        return new JsonResponse($data, Response::HTTP_CREATED);
    }
}