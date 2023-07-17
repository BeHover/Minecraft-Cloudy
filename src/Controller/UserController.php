<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Main\User;
use App\Repository\Authme\AuthmeRepository;
use App\Repository\Main\UserRepository;
use App\Security\EmailVerifier;
use App\Service\JWTTokenService;
use App\Service\OTPService;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private AuthmeRepository $authmeRepository;
    private UserPasswordHasherInterface $userPasswordHasher;
    private UserPasswordEncoderInterface $passwordEncoder;
    private JWTTokenService $jwtManager;
    private EmailVerifier $emailVerifier;
    private Filesystem $fileSystem;
    private OTPService $OTPService;

    public function __construct(
        UserRepository               $userRepository,
        AuthmeRepository             $authmeRepository,
        UserPasswordHasherInterface  $userPasswordHasher,
        UserPasswordEncoderInterface $passwordEncoder,
        JWTTokenService              $jwtManager,
        EmailVerifier                $emailVerifier,
        Filesystem                   $fileSystem,
        OTPService                   $OTPService
    )
    {
        $this->userRepository = $userRepository;
        $this->authmeRepository = $authmeRepository;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->passwordEncoder = $passwordEncoder;
        $this->jwtManager = $jwtManager;
        $this->emailVerifier = $emailVerifier;
        $this->fileSystem = $fileSystem;
        $this->OTPService = $OTPService;
    }

    /**
     * @Route(path="/api/login", name="userLogin", methods={"POST"})
     * @throws UserNotFoundException
     */
    public function userLogin(
        Request $request
    ): JsonResponse
    {
        $credentials = json_decode($request->getContent(), true);

        $username = $credentials["username"] ?? null;
        $password = $credentials["password"] ?? null;

        if (!$username || !$password) {
            return new JsonResponse([
                "message" => "Проверьте ввод всех обязательных полей"
            ], 401);
        }

        $user = $this->userRepository->findOneBy(["username" => $username]);

        if (!$user || !$this->passwordEncoder->isPasswordValid($user, $password)) {
            return new JsonResponse([
                "message" => "Вы указали неверный логин или пароль"
            ], 401);
        }

        $token = $this->jwtManager->createToken($user);

        return new JsonResponse(["token" => $token]);
    }

    /**
     * @Route(path="/api/register", name="userRegister", methods={"POST"})
     * @throws UserNotFoundException
     */
    public function userRegister(
        Request $request
    ): JsonResponse
    {
        $credentials = json_decode($request->getContent(), true);

        $username = $credentials["username"] ?? null;
        $password = $credentials["password"] ?? null;
        $email = $credentials["email"] ?? null;

        if (!$username || !$password || !$email) {
            return new JsonResponse([
                "message" => "Проверьте ввод всех обязательных полей"
            ], 401);
        }

        if ($this->userRepository->findOneBy(["username" => $username]) !== null) {
            return new JsonResponse([
                "message" => "Аккаунт с таким игровым никнеймом уже существует"
            ], 401);
        }

        if ($this->userRepository->findOneBy(["email" => $email]) !== null) {
            return new JsonResponse([
                "message" => "К сожалению, эту почту уже кто-то использует"
            ], 401);
        }

        if (!preg_match("/^\w{4,20}$/", $username)) {
            return new JsonResponse([
                "message" => "Никнейм должен содержать исключительно латинские символы. Допустимая длина - от 4 до 20 символов."
            ], 401);
        }

        if (!preg_match("/^\w{5,24}$/", $password)) {
            return new JsonResponse([
                "message" => "Пароль должен состоять из латиницы и цифр. Допустимая длина - от 5 до 24 символов."
            ], 401);
        }

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        $user->setEmail($email);
        $user->setRoles(["ROLE_USER"]);
        $user->setSkin("$username.png");
        $user->setIsVerified(true);

        try {
            $user->setCreatedAt(new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga")));
            $user->setUpdatedAt(new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga")));
        } catch (\Exception) {
            return new JsonResponse([
                "message" => "Ошибка даты и времени, обратитесь в службу поддержки"
            ], 401);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $cwd = getcwd();
        $this->fileSystem->copy(
            "$cwd/default/skin.png",
            "$cwd/images/skins/$username.png"
        );

        $entityManager->persist($user);
        $entityManager->flush();

        try {
            $code = $this->OTPService->generateOTP($user);
        } catch (Exception $e) {
            return new JsonResponse([
                "message" => "Ошибка генерации OTP кода, обратитесь в службу поддержки"
            ], 500);
        }

        $this->emailVerifier->sendEmailConfirmation($user,
            (new TemplatedEmail())
                ->to($user->getEmail())
                ->subject("Подтверждение почты")
                ->htmlTemplate("email/confirmation_email.html.twig"),
            $code
        );

        $token = $this->jwtManager->createToken($user);

        return new JsonResponse(["token" => $token]);
    }

    /**
     * @Route(path="/api/user/verify-email", name="userVerifyEmail", methods={"POST"})
     * @throws UserNotFoundException
     */
    public function verifyUserEmail(
        Request $request
    ): Response
    {
        return $request->getContent()["TODO"];
    }

    /**
     * @Route(path="/api/user", name="getUserData", methods={"GET"})
     */
    public function getUserData(
        Request $request
    ): JsonResponse
    {
        $token = null;

        $authorizationHeader = $request->headers->get('Authorization');

        if ($authorizationHeader && preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            $token = $matches[1];
        }

        if ($token === null) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя"
            ], 401);
        }

        try {
            $payload = new PreAuthenticationJWTUserToken($token);
            $decoded = $this->jwtManager->decodeToken($payload);
        } catch (JWTDecodeFailureException|ExpiredTokenException $e) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя"
            ], 401);
        }

        $user = $this->userRepository->findOneBy(["username" => $decoded["username"]]);

        if (!$user) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя"
            ], 401);
        }

        $data = [
            "username" => $user->getUserIdentifier(),
            "email" => $user->getEmail(),
            "createdAt" => $user->getCreatedAt()->format('d.m.Y') . " в " . $user->getCreatedAt()->format('H:i'),
            "updatedAt" => $user->getUpdatedAt()->format('d.m.Y') . " в " . $user->getUpdatedAt()->format('H:i'),
            "verified" => $user->isVerified(),
            "roles" => $user->getRoles()
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route(path="/api/user/change-password", name="changeUserPassword", methods={"POST"})
     */
    public function changeUserPassword(
        Request $request
    ): JsonResponse
    {
        $credentials = json_decode($request->getContent(), true);

        $nowPassword = $credentials["nowPassword"] ?? null;
        $newPassword = $credentials["newPassword"] ?? null;
        $repeatPassword = $credentials["repeatPassword"] ?? null;
        $token = $credentials["token"] ?? null;

        if ($token === null) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя"
            ], 401);
        }

        try {
            $payload = new PreAuthenticationJWTUserToken($token);
            $decoded = $this->jwtManager->decodeToken($payload);
        } catch (JWTDecodeFailureException|ExpiredTokenException $e) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя"
            ], 401);
        }

        $user = $this->userRepository->findOneBy(["username" => $decoded["username"]]);

        if (!$user) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя"
            ], 401);
        }

        if (!$nowPassword || !$newPassword || !$repeatPassword) {
            return new JsonResponse([
                "message" => "Проверьте ввод всех обязательных полей"
            ], 401);
        }

        if (!$this->passwordEncoder->isPasswordValid($user, $nowPassword)) {
            return new JsonResponse([
                "message" => "Вы неверно указали текущий пароль от аккаунта"
            ], 401);
        }

        if ($newPassword !== $repeatPassword) {
            return new JsonResponse([
                "message" => "Пароли не совпадают"
            ], 401);
        }

        $user->setPassword($this->userPasswordHasher->hashPassword($user, $newPassword));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }
}