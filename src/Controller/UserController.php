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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;

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

    public function __construct(
        UserRepository               $userRepository,
        JWTTokenService              $JWTTokenService,
        AuthmeRepository             $authmeRepository,
        UserPasswordHasherInterface  $userPasswordHasher,
        UserPasswordEncoderInterface $passwordEncoder,
        EmailVerifier                $emailVerifier,
        Filesystem                   $fileSystem,
        OTPService                   $OTPService,
        OTPRepository                $OTPRepository
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
    }

    #[Route('/login', name: 'user_login', methods: ['POST'])]
    public function userLogin(
        Request $plainRequest
    ): JsonResponse
    {
        $requestData = $plainRequest->toArray();
        $username = $requestData["username"];
        $password = $requestData["password"];

        if (empty(trim($username)) || empty(trim($password))) {
            return new JsonResponse([
                "message" => "Проверьте ввод всех обязательных полей"
            ], 401);
        }


        if (!preg_match("/^\w{4,20}$/", $username)) {
            return new JsonResponse([
                "message" => "Ошибка ввода имени пользователя."
            ], 401);
        }

        if (!preg_match("/^\w{5,24}$/", $password)) {
            return new JsonResponse([
                "message" => "Пароль не соответствует необходимому формату."
            ], 401);
        }

        $user = $this->userRepository->findOneBy(["username" => $username]);

        if (null === $user) {
            return new JsonResponse([
                "message" => "Пользователь с таким набором данных не найден."
            ], 401);
        }

        if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
            return new JsonResponse([
                "message" => "Вы указали неверный пароль от аккаунта."
            ], 401);
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

        return new JsonResponse($data, 200);
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

        if (!$username || !$password || !$email) {
            return new JsonResponse([
                "message" => "Проверьте ввод всех обязательных полей."
            ], 401);
        }

        if ($this->userRepository->findOneBy(["username" => $username]) !== null) {
            return new JsonResponse([
                "message" => "Аккаунт с таким игровым никнеймом уже существует."
            ], 401);
        }

        if ($this->userRepository->findOneBy(["email" => $email]) !== null) {
            return new JsonResponse([
                "message" => "К сожалению, эту почту уже кто-то использует."
            ], 401);
        }

        if (!preg_match("/^\w{4,20}$/", $username)) {
            return new JsonResponse([
                "message" => "Никнейм должен содержать исключительно латинские символы. Допустимая длина - от 4 до 20 символов."
            ], 401);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse([
                "message" => "Введите действительную электронную почту."
            ], 401);
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
            return new JsonResponse([
                "message" => "Ошибка генерации OTP кода, обратитесь в службу поддержки."
            ], 500);
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

        return new JsonResponse($data, 200);
    }

    #[Route('/verify', name: 'user_verify', methods: ['POST'])]
    public function userVerify(
        Request $plainRequest
    ): JsonResponse
    {
        $requestData = $plainRequest->toArray();
        $otp = $requestData["otp"];

        if (!$otp || !preg_match("/^\d{6}$/", $otp)) {
            return new JsonResponse([
                "message" => "Вы ввели некорректный OTP код."
            ], 401);
        }

        /** @var User $user */
        $user = $this->getUser();

        $cache = $this->OTPRepository->findOneBy(["user" => $user]);

        if ($cache === null) {
            return new JsonResponse([
                "message" => "Произошла ошибка при генерации OTP кода. Запросите его ещё раз."
            ], 401);
        }

        if ((int) $otp !== $cache->getOTP()) {
            return new JsonResponse([
                "message" => "Вы указали неверный OTP код. Повторите попытку или запросите его ещё раз."
            ], 401);
        }

        $this->OTPRepository->remove($cache, true);

        $user->setIsVerified(true);
        $this->userRepository->save($user, true);

        if (null === $this->authmeRepository->findOneBy(["realname" => $user->getUsername()])) {
            $authmeUser = new Authme($user->getUsername(), $user->getPassword());
            $this->authmeRepository->save($authmeUser, true);

            return new JsonResponse([
                "message" => "Вы успешно подтвердили почту с помощью OTP кода. Теперь вы можете играть на сервере."
            ], 200);
        }

        return new JsonResponse([
            "message" => "Вы успешно подтвердили почту с помощью OTP кода."
        ], 200);
    }

    #[Route('/verify/send', name: 'send_otp', methods: ['GET'])]
    public function sendOTP(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        try {
            $code = $this->OTPService->generateOTP($user);
        } catch (Exception $e) {
            return new JsonResponse([
                "message" => "Ошибка генерации OTP кода, обратитесь в службу поддержки."
            ], 500);
        }

        $this->emailVerifier->sendEmailConfirmation($user,
            (new TemplatedEmail())
                ->to($user->getEmail())
                ->subject("Подтверждение почты")
                ->htmlTemplate("email/confirmation_email.html.twig"),
            $code
        );

        return new JsonResponse([
            "message" => "Код подтверждения успешно отправлен на вашу почту."
        ], 200);
    }
}