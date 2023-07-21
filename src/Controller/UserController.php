<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Authme\Authme;
use App\Entity\Main\User;
use App\Repository\Authme\AuthmeRepository;
use App\Repository\Main\OTPRepository;
use App\Repository\Main\UserRepository;
use App\Security\EmailVerifier;
use App\Service\JWTTokenService;
use App\Service\OTPService;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('api/users')]
class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private JWTTokenService $JWTTokenService;
    private AuthmeRepository $authmeRepository;
    private UserPasswordHasherInterface $userPasswordHasher;
    private UserPasswordEncoderInterface $passwordEncoder;
    private EmailVerifier $emailVerifier;
    private Filesystem $fileSystem;
    private OTPService $OTPService;
    private OTPRepository $OTPRepository;
    private TranslatorInterface $translator;

    public function __construct(
        UserRepository               $userRepository,
        JWTTokenService              $JWTTokenService,
        AuthmeRepository             $authmeRepository,
        UserPasswordHasherInterface  $userPasswordHasher,
        UserPasswordEncoderInterface $passwordEncoder,
        EmailVerifier                $emailVerifier,
        Filesystem                   $fileSystem,
        OTPService                   $OTPService,
        OTPRepository                $OTPRepository,
        TranslatorInterface          $translator
    )
    {
        $this->userRepository = $userRepository;
        $this->JWTTokenService = $JWTTokenService;
        $this->authmeRepository = $authmeRepository;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->passwordEncoder = $passwordEncoder;
        $this->emailVerifier = $emailVerifier;
        $this->fileSystem = $fileSystem;
        $this->OTPService = $OTPService;
        $this->OTPRepository = $OTPRepository;
        $this->translator = $translator;
    }

    #[Route('/login', name: 'user_login', methods: ['POST'])]
    public function userLogin(
        Request $plainRequest
    ): JsonResponse
    {
        $requestData = $plainRequest->toArray();
        $username = $requestData["username"];
        $password = $requestData["password"];
        $locale = $plainRequest->query->get("locale", "en_EN");

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
            "username" => $user->getUserIdentifier(),
            "email" => $user->getEmail(),
            "createdAt" => $user->getCreatedAt()->format('d.m.Y') . " в " . $user->getCreatedAt()->format('H:i'),
            "updatedAt" => $user->getUpdatedAt()->format('d.m.Y') . " в " . $user->getUpdatedAt()->format('H:i'),
            "verified" => $user->isVerified(),
            "roles" => $user->getRoles()
        ];

        return new JsonResponse($data);
    }

    #[Route('/register', name: 'user_register', methods: ['POST'])]
    public function userRegister(
        Request $plainRequest
    ): JsonResponse
    {
        $requestData = $plainRequest->toArray();
        $username = $requestData["username"];
        $password = $requestData["password"];
        $email = $requestData["email"];
        $locale = $plainRequest->query->get("locale", "en_EN");

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
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
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
            "username" => $user->getUserIdentifier(),
            "email" => $user->getEmail(),
            "createdAt" => $user->getCreatedAt()->format('d.m.Y') . " в " . $user->getCreatedAt()->format('H:i'),
            "updatedAt" => $user->getUpdatedAt()->format('d.m.Y') . " в " . $user->getUpdatedAt()->format('H:i'),
            "verified" => $user->isVerified(),
            "roles" => $user->getRoles()
        ];

        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    #[Route('/verify', name: 'user_verify', methods: ['POST'])]
    public function userVerify(
        Request $plainRequest
    ): JsonResponse
    {
        $requestData = $plainRequest->toArray();
        $otp = $requestData["otp"];
        $locale = $plainRequest->query->get("locale", "en_EN");

        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!$otp || !preg_match("/^\d{6}$/", $otp)) {
            $message = $this->translator->trans("user.otp.invalid", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_NOT_FOUND);
        }

        /** @var User $user */
        $user = $this->getUser();

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

    #[Route('/verify/send', name: 'send_otp', methods: ['GET'])]
    public function sendOTP(
        Request $plainRequest
    ): JsonResponse
    {
        $locale = $plainRequest->query->get("locale", "en_EN");

        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        /** @var User $user */
        $user = $this->getUser();

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