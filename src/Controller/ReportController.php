<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\ReportsDataResolver;
use App\Entity\Main\Report;
use App\Entity\Main\ReportChatMessage;
use App\Entity\Main\ReportType;
use App\Entity\Main\User;
use App\Repository\Main\ReportChatMessageRepository;
use App\Repository\Main\ReportRepository;
use App\Repository\Main\ReportTypeRepository;
use App\Repository\Main\UserRepository;
use DateTimeImmutable;
use DateTimeZone;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/reports')]
class ReportController extends AbstractController
{
    private ReportRepository $reportRepository;
    private ReportChatMessageRepository $reportChatMessageRepository;
    private ReportTypeRepository $reportTypeRepository;
    private ReportsDataResolver $reportsDataResolver;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        ReportRepository $reportRepository,
        ReportChatMessageRepository $reportChatMessageRepository,
        ReportTypeRepository $reportTypeRepository,
        ReportsDataResolver $reportsDataResolver,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->reportRepository = $reportRepository;
        $this->reportChatMessageRepository = $reportChatMessageRepository;
        $this->reportTypeRepository = $reportTypeRepository;
        $this->reportsDataResolver = $reportsDataResolver;
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    #[Route('', name: 'all_reports', methods: ['GET'])]
    public function getAllReports() : JsonResponse {
        $reports = $this->reportsDataResolver->getAllReports();

        return new JsonResponse($reports);
    }

    #[Route('/create', name: 'create_report', methods: ['GET'])]
    public function createReport() : JsonResponse {
        if ($this->userRepository->findBy(["username" => "CHYZHOV"])) {
            return new JsonResponse([
                "message" => "Действие уже выполнялось."
            ], 401);
        }

        $username = "CHYZHOV";
        $email = "chyzhov.contact@gmail.com";
        $user = new User($username, $email);

        $password = "1115820";
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        $this->userRepository->save($user, true);

        $title = "Жалоба на игрока";
        $type = new ReportType($title);
        $this->reportTypeRepository->save($type, true);

        $text = "Я обращаюсь к Вам с жалобой на игрока PaRaDoX3727, который нарушает правила сервера и влияет на игровой опыт других пользователей. Я надеюсь, что моё сообщение поможет создать более приятную и дружелюбную обстановку на сервере.";
        $answer = "Мы взяли вашу жалобу во внимание. Игрок будет наказан.";

        $report = new Report($user, $type, $text);
        $this->reportRepository->save($report, true);

        $message = new ReportChatMessage($report, $user, $answer);
        $this->reportChatMessageRepository->save($message, true);

        return new JsonResponse([
            "reportId" => $report->getId(),
            "messageId" => $message->getId(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/{reportId}', name: 'report_data', methods: ['GET'])]
    public function getReportById(string $reportId) : JsonResponse {
        $reportUuid = Uuid::fromString($reportId);
        $report = $this->reportsDataResolver->getReportById((string) $reportUuid);

        if (empty($report)) {
            return new JsonResponse(["message" => "Тикет с таким идентификатором не найден."]);
        }

        return new JsonResponse($report);
    }

    #[Route('/{reportId}/message', name: 'create_message', methods: ['GET'])]
    public function createMessage(string $reportId) : JsonResponse {
        $reportUuid = Uuid::fromString($reportId);
        $report = $this->reportRepository->findOneBy(["id" => $reportUuid]);
//        dd($report);

        if (null === $report) {
            return new JsonResponse(["message" => "Тикет с таким идентификатором не найден."]);
        }

        $user = $this->userRepository->findOneBy(["username" => "CHYZHOV"]);
        $answer = "Спасибо за обращение в службу поддержки! Хорошей игры.";

        $message = new ReportChatMessage($report, $user, $answer);
        $this->reportChatMessageRepository->save($message, true);

        try {
            $report->setClosedAt(new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga")));
        } catch (\Exception $e) {
        }

        $report->setClosedBy($user);
        $this->reportRepository->save($report, true);

        $reportData = $this->reportsDataResolver->getReportById((string) $reportUuid);

        return new JsonResponse($reportData);
    }
}