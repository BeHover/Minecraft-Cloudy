<?php

namespace App\Controller;

use App\Event\FileToDeleteEvent;
use App\Form\ChangePasswordType;
use App\Form\ProfileChangeEmailType;
use App\Form\ProfileChangeSkinType;
use App\Form\ProfileChangePasswordType;
use App\Form\RecoverPasswordType;
use App\Repository\Authme\AuthmeRepository;
use App\Repository\Main\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use ReallySimpleJWT\Token;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class PlayerController extends AbstractController
{
    private EventDispatcherInterface $eventDispatcher;
    private EmailVerifier $emailVerifier;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EmailVerifier $emailVerifier,
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->emailVerifier = $emailVerifier;
    }

    public function login(
        AuthenticationUtils $authenticationUtils
    ) : Response {
        if ($this->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("profile");
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render("pages/account/login.html.twig", [
            "error" => $error,
            "success" => null
        ]);
    }

    public function profile (
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        AuthmeRepository $authmeRepository,
        UserRepository $userRepository
    ): Response
    {
        if (!$this->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute('login');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManagerAuthme = $this->getDoctrine()->getManager("authme");

        $user = $this->getUser();

        $authmeUser = $authmeRepository->findOneBy(["realname" => $user->getUserIdentifier()]);
        $cloudyUser = $userRepository->findOneBy(["username" => $user->getUserIdentifier()]);

        if (!$cloudyUser->isVerified()) {
            return $this->render("pages/account/not_verified.html.twig");
        }

        $formChangePassword = $this->createForm(ProfileChangePasswordType::class, $user);
        $formChangeEmail = $this->createForm(ProfileChangeEmailType::class, $user);
        $formChangeSkin = $this->createForm(ProfileChangeSkinType::class, $user);

        $formChangePassword->handleRequest($request);
        $formChangeEmail->handleRequest($request);
        $formChangeSkin->handleRequest($request);

        if ($formChangeSkin->isSubmitted() && $formChangeSkin->isValid()) {
            $skinFile = $formChangeSkin->get("skin")->getData();

            if ($skinFile) {
                $newFilename = $user->getUsername().".".$skinFile->guessExtension();

                try {
                    $skinFile->move(
                        $this->getParameter("app.skin.dir"),
                        $newFilename
                    );
                } catch (FileException $e) {
                    var_dump($e);
                }

                if ($user->getSkin()) {
                    $fullPath = "{$this->getParameter("app.skin.dir")}\\{$user->getSkin()}";
                    $event = new FileToDeleteEvent($fullPath);
                    $this->eventDispatcher->dispatch($event, FileToDeleteEvent::NAME);
                }
                $user->setSkin($newFilename);
            }
            $entityManager->flush();

            return $this->renderForm(
                "pages/profile/account.html.twig",
                [
                    "formChangePassword" => $formChangePassword,
                    "formChangeEmail" => $formChangeEmail,
                    "formChangeSkin" => $formChangeSkin,
                    "message" => "Вы успешно установили новый скин для своего персонажа."
                ]
            );
        }

        if ($formChangePassword->isSubmitted() && $formChangePassword->isValid()) {
            $password = $formChangePassword->get("password")->getData();

            if ($password) {
                if (!preg_match("/^\w{5,24}$/", $password)) {
                    return $this->renderForm("pages/profile/account.html.twig",
                        [
                            "formChangePassword" => $formChangePassword,
                            "formChangeEmail" => $formChangeEmail,
                            "formChangeSkin" => $formChangeSkin,
                            "message" => "Пароль должен состоять из латиницы и цифр с длиной от 5 до 24 символов."
                        ]
                    );
                }
                $hashedPassword = $userPasswordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
                $authmeUser->setPassword($hashedPassword);
            }

            $entityManagerAuthme->flush();
            $entityManager->flush();

            return $this->renderForm("pages/profile/account.html.twig",
                [
                    "formChangePassword" => $formChangePassword,
                    "formChangeEmail" => $formChangeEmail,
                    "formChangeSkin" => $formChangeSkin,
                    "message" => "Теперь вы можете использовать новый пароль."
                ]
            );
        }

        if ($formChangeEmail->isSubmitted() && $formChangeEmail->isValid()) {
            $email = $formChangeEmail->get("email")->getData();

            if ($email) {
                if (null !== $userRepository->findOneBy(["email" => $email])) {
                    return $this->renderForm("pages/profile/account.html.twig",
                        [
                            "formChangePassword" => $formChangePassword,
                            "formChangeEmail" => $formChangeEmail,
                            "formChangeSkin" => $formChangeSkin,
                            "message" => "Пользователь с таким адресом электронной почты уже зарегистрирован."
                        ]
                    );
                }

                $user->setEmail($email);
                $this->emailVerifier->sendEmailConfirmation("verify_email", $user,
                    (new TemplatedEmail())
                        ->to($email)
                        ->subject("Подтверждение почты")
                        ->htmlTemplate("email/confirmation_email.html.twig")
                );
                $user->setIsVerified(false);
            }
            $entityManagerAuthme->flush();
            $entityManager->flush();

            return $this->renderForm("pages/profile/account.html.twig",
                [
                    "formChangePassword" => $formChangePassword,
                    "formChangeEmail" => $formChangeEmail,
                    "formChangeSkin" => $formChangeSkin,
                    "message" => "Мы выслали вам письмо для подтверждения новой почты."
                ]
            );
        }

        return $this->renderForm(
            "pages/profile/account.html.twig",
            [
                "formChangePassword" => $formChangePassword,
                "formChangeEmail" => $formChangeEmail,
                "formChangeSkin" => $formChangeSkin,
                "message" => null
            ]
        );
    }

    public function sendPasswordRecovery(
        Request $request,
        MailerInterface $mailer,
        UserRepository $userRepository,
        $tokenSecret
    ): Response {
        $form = $this->createForm(RecoverPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get("email")->getData();

            $user = $userRepository->findOneBy(["email" => $email]);
            if (null === $user) {
                return $this->renderForm(
                    "pages/account/send_password_recovery.html.twig",
                    [
                        "recoveryForm" => $form,
                        "message" => "Пользователь с таким адресом не найден.",
                        "success" => 0
                    ]
                );
            }

            $token = Token::customPayload(
                [
                    "userId" => $user->getId()
                ],
                $tokenSecret
            );

            $mail = (new TemplatedEmail())
                ->to($email)
                ->subject("Восстановление доступа к аккаунту")
                ->htmlTemplate("email/send_password_recovery.html.twig")
                ->context([
                    "signedUrl" => $this->generateUrl(
                        "password_recovery",
                        ["token" => $token],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ])
            ;

            try {
                $mailer->send($mail);
            } catch (TransportExceptionInterface) {
                #TODO: handle exception properly!
            }

            return $this->renderForm(
                "pages/account/send_password_recovery.html.twig",
                [
                    "recoveryForm" => $form,
                    "message" => null,
                    "success" => 1
                ]
            );
        }

        return $this->renderForm(
            "pages/account/send_password_recovery.html.twig",
            [
                "recoveryForm" => $form,
                "message" => null,
                "success" => 0
            ]
        );
    }

    public function recoveryPassword(
        Request $request,
        string $token,
        $tokenSecret,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
    ): RedirectResponse|Response {
        $userId = Token::getPayload($token, $tokenSecret)["userId"];
        $user = $userRepository->find($userId);

        if (null === $user) {
            return $this->redirectToRoute("main_page");
        }

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get("password")->getData();
            $plainPasswordConfirm = $form->get("confirmPassword")->getData();

            if ($plainPassword !== $plainPasswordConfirm) {
                return $this->renderForm(
                    "pages/account/password_recovery.html.twig",
                    [
                        "recoveryForm" => $form,
                        "message" => "Вы указали разные пароли, повторите попытку."
                    ]
                );
            }

            if (!preg_match("/^\w{5,24}$/", $plainPassword)) {
                return $this->renderForm(
                    "pages/account/password_recovery.html.twig",
                    [
                        "recoveryForm" => $form,
                        "message" => "Пароль должен состоять из латиницы и цифр с длиной от 5 до 24 символов."
                    ]
                );
            }

            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $entityManager->flush();

            $email = $user->getEmail();

            $mail = (new TemplatedEmail())
                ->to($email)
                ->subject("Изменение пароля")
                ->htmlTemplate("email/password_recovery.html.twig")
                ->context([
                    "username" => $user->getUsername(),
                    "password" => $plainPassword
                ]);

            try {
                $mailer->send($mail);
            } catch (TransportExceptionInterface) {
                #TODO: handle exception properly!
            }

            return $this->redirectToRoute("profile");
        }

        return $this->renderForm(
            "pages/account/password_recovery.html.twig",
            [
                "recoveryForm" => $form,
                "message" => null
            ]
        );
    }
}
