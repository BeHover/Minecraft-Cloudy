<?php

namespace App\Controller;

use App\Entity\Authme\Authme;
use App\Entity\Main\User;
use App\Form\RegistrationFormType;
use App\Repository\Authme\AuthmeRepository;
use App\Repository\Main\UserRepository;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private Filesystem $filesystem;

    public function __construct(EmailVerifier $emailVerifier, Filesystem $filesystem)
    {
        $this->emailVerifier = $emailVerifier;
        $this->filesystem = $filesystem;
    }

    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserRepository $userRepository
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get("plainPassword")->getData();
            if (!preg_match("/^\w{5,24}$/", $plainPassword)) {
                return $this->render("pages/account/register.html.twig",
                    [
                        "registrationForm" => $form->createView(),
                        "message" => "Пароль должен состоять из латиницы и цифр. Допустимая длина: от 5 до 24 символов."
                    ]
                );
            }

            $userName = $form->get("username")->getData();
            if (null !== $userRepository->findOneBy(["username" => $userName])) {
                return $this->render("pages/account/register.html.twig",
                    [
                        "registrationForm" => $form->createView(),
                        "message" => "Этот никнейм уже кем-то занят."
                    ]
                );
            }

            if (!preg_match("/^\w{5,20}$/", $userName)) {
                return $this->render("pages/account/register.html.twig",
                    [
                        "registrationForm" => $form->createView(),
                        "message" => "Никнейм должен состоять из латиницы и цифр. Допустимая длина: от 5 до 20 символов."
                    ]
                );
            }

            $email = $form->get("email")->getData();
            if (null !== $userRepository->findOneBy(["email" => $email])) {
                return $this->render("pages/account/register.html.twig",
                    [
                        "registrationForm" => $form->createView(),
                        "message" => "Эту почту уже кто-то использует."
                    ]
                );
            }

            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);

            $user->setUsername($userName);
            $user->setEmail($email);
            $user->setPassword($hashedPassword);
            $user->setRoles(["ROLE_PLAYER", "ROLE_USER"]);

            $entityManager = $this->getDoctrine()->getManager();

            $cwd = getcwd();
            $this->filesystem->copy(
                "$cwd/default/skin.png",
                "$cwd/images/skins/$userName.png"
            );
            $user->setSkin("$userName.png");

            $user->setIsVerified(false);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->emailVerifier->sendEmailConfirmation("verify_email", $user,
                (new TemplatedEmail())
                    ->to($user->getEmail())
                    ->subject("Подтверждение почты")
                    ->htmlTemplate("email/confirmation_email.html.twig")
            );

            return $this->redirectToRoute("login");
        }

        return $this->render("pages/account/register.html.twig",
            [
                "registrationForm" => $form->createView(),
                "message" => null
            ]
        );
    }

    public function verifyUserEmail(
        AuthmeRepository $authmeRepository,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $user = $this->getUser();

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);

            $entityManagerAuthme = $this->getDoctrine()->getManager("authme");
            if (null === $authmeRepository->findOneBy(["realname" => $user->getUsername()])) {
                $authmeUser = new Authme();
                $authmeUser->setUsername(strtolower($user->getUsername()));
                $authmeUser->setRealname($user->getUsername());
                $authmeUser->setPassword($user->getPassword());
                $authmeUser->setRegdate(time());
                $entityManagerAuthme->persist($authmeUser);
                $entityManagerAuthme->flush();
            }
        } catch (VerifyEmailExceptionInterface) {
            return $this->redirectToRoute("profile");
        }

        return $this->redirectToRoute("profile");
    }

    public function sendVerify(): Response {
        $user = $this->getUser();

        if (null !== $user && !$user->isVerified()) {
            $this->emailVerifier->sendEmailConfirmation("verify_email", $user,
                (new TemplatedEmail())
                    ->to($user->getEmail())
                    ->subject("Подтверждение почты")
                    ->htmlTemplate("email/confirmation_email.html.twig")
            );
        }

        return $this->redirectToRoute("profile");
    }
}
