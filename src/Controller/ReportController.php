<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\ReportsDataResolver;
use App\Entity\Main\Report;
use App\Entity\Main\ReportChatMessage;
use App\Entity\Main\ReportType;
use App\Repository\Main\ReportChatMessageRepository;
use App\Repository\Main\ReportRepository;
use App\Repository\Main\ReportTypeRepository;
use App\Repository\Main\UserRepository;
use App\Service\JWTTokenService;
use DateTimeImmutable;
use DateTimeZone;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    private JWTTokenService $JWTTokenService;

    public function __construct(
        ReportRepository $reportRepository,
        ReportChatMessageRepository $reportChatMessageRepository,
        ReportTypeRepository $reportTypeRepository,
        ReportsDataResolver $reportsDataResolver,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        JWTTokenService $JWTTokenService
    ) {
        $this->reportRepository = $reportRepository;
        $this->reportChatMessageRepository = $reportChatMessageRepository;
        $this->reportTypeRepository = $reportTypeRepository;
        $this->reportsDataResolver = $reportsDataResolver;
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->JWTTokenService = $JWTTokenService;
    }

    #[Route('', name: 'all_reports', methods: ['GET'])]
    public function getAllReports() : JsonResponse {
        $reports = $this->reportsDataResolver->getAllReports();

        return new JsonResponse($reports);
    }

    #[Route('/types', name: 'all_report_types', methods: ['GET'])]
    public function getAllReportTypes() : JsonResponse {
        $data = $this->reportTypeRepository->findAll();
        $types = [];

        foreach ($data as $type) {
            $types[] = [
                "uuid" => $type->getId(),
                "name" => $type->getName(),
                "createdAt" => $type->getCreatedAt(),
            ];
        }

        return new JsonResponse($types);
    }

    #[Route('/types/create', name: 'create_report_type', methods: ['POST'])]
    public function createReportType(Request $request) : JsonResponse {
        $credentials = json_decode($request->getContent(), true);
        $token = $credentials["token"] ?? null;
        $name = $credentials["name"] ?? null;

        if ($token === null) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя."
            ], 401);
        }

        if ($name === null) {
            return new JsonResponse([
                "message" => "Укажите названия для создания этого типа обращения."
            ], 401);
        }

        if ($this->reportTypeRepository->findOneBy(["name" => $name]) !== null) {
            return new JsonResponse([
                "message" => "Такое название уже используется, выберите другое."
            ], 401);
        }

        try {
            $payload = new PreAuthenticationJWTUserToken($token);
            $decoded = $this->JWTTokenService->decodeToken($payload);
        } catch (JWTDecodeFailureException|ExpiredTokenException $e) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя."
            ], 401);
        }

        $user = $this->userRepository->findOneBy(["username" => $decoded["username"]]);

        if (!$user) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя"
            ], 401);
        }

        $type = new ReportType($name);
        $this->reportTypeRepository->save($type, true);

        return new JsonResponse([
            "message" => "Новый тип обращения в службу поддержки успешно создан."
        ], Response::HTTP_CREATED);
    }

    #[Route('/create', name: 'create_report', methods: ['POST'])]
    public function createReport(Request $request) : JsonResponse {
        $credentials = json_decode($request->getContent(), true);
        $token = $credentials["token"] ?? null;
        $typeUuid = $credentials["type"] ?? null;
        $text = $credentials["text"] ?? null;

        if ($token === null) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя."
            ], 401);
        }

        if ($typeUuid === null || $text === null) {
            return new JsonResponse([
                "message" => "Недостаточно данных для регистрации обращения."
            ], 401);
        }

        $type = $this->reportTypeRepository->findOneBy(["id" => $typeUuid]);

        if ($type === null) {
            return new JsonResponse([
                "message" => "Ошибка выбора категории обращения в службу поддержки."
            ], 401);
        }

        try {
            $payload = new PreAuthenticationJWTUserToken($token);
            $decoded = $this->JWTTokenService->decodeToken($payload);
        } catch (JWTDecodeFailureException|ExpiredTokenException $e) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя."
            ], 401);
        }

        $user = $this->userRepository->findOneBy(["username" => $decoded["username"]]);

        if (!$user) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя"
            ], 401);
        }

        $report = new Report($user, $type, $text);
        $this->reportRepository->save($report, true);

        return new JsonResponse([
            "message" => "Ваше обращение в службу поддержки успешно зарегистрировано."
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

    #[Route('/{reportId}/message', name: 'post_message', methods: ['POST'])]
    public function postMessage(Request $request, string $reportId) : JsonResponse {
        $credentials = json_decode($request->getContent(), true);
        $token = $credentials["token"] ?? null;
        $text = $credentials["message"] ?? null;

        if ($token === null) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя."
            ], 401);
        }

        if ($text === null) {
            return new JsonResponse([
                "message" => "Нельзя отправить сообщений без текста."
            ], 401);
        }

        try {
            $payload = new PreAuthenticationJWTUserToken($token);
            $decoded = $this->JWTTokenService->decodeToken($payload);
        } catch (JWTDecodeFailureException|ExpiredTokenException $e) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя."
            ], 401);
        }

        $user = $this->userRepository->findOneBy(["username" => $decoded["username"]]);

        if (!$user) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя"
            ], 401);
        }

        $reportUuid = Uuid::fromString($reportId);
        $report = $this->reportRepository->findOneBy(["id" => $reportUuid]);

        if (null === $report) {
            return new JsonResponse(["message" => "Тикет с таким идентификатором не найден."]);
        }

        if ($report->getCreatedBy() !== $user || !in_array("ROLE_ADMIN", $user->getRoles())) {
            return new JsonResponse(["message" => "Недостаточно полномочий для выполнения этого действия."]);
        }

        $message = new ReportChatMessage($report, $user, $text);
        $this->reportChatMessageRepository->save($message, true);

        return new JsonResponse([
            "message" => "Ваше сообщение успешно добавлено в чат со службой поддержки."
        ], 200);
    }

    #[Route('/{reportId}/deactivate', name: 'deactivate_report', methods: ['POST'])]
    public function deactivateReport(Request $request, string $reportId) : JsonResponse {
        $credentials = json_decode($request->getContent(), true);
        $token = $credentials["token"] ?? null;

        if ($token === null) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя."
            ], 401);
        }

        try {
            $payload = new PreAuthenticationJWTUserToken($token);
            $decoded = $this->JWTTokenService->decodeToken($payload);
        } catch (JWTDecodeFailureException|ExpiredTokenException $e) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя."
            ], 401);
        }

        $user = $this->userRepository->findOneBy(["username" => $decoded["username"]]);

        if (!$user) {
            return new JsonResponse([
                "message" => "Ошибка сессии текущего пользователя"
            ], 401);
        }

        $reportUuid = Uuid::fromString($reportId);
        $report = $this->reportRepository->findOneBy(["id" => $reportUuid]);

        if (null === $report) {
            return new JsonResponse(["message" => "Тикет с таким идентификатором не найден."]);
        }

        if ($report->getCreatedBy() !== $user || !in_array("ROLE_ADMIN", $user->getRoles())) {
            return new JsonResponse(["message" => "Недостаточно полномочий для выполнения этого действия."]);
        }

        $report->setStatus(0);
        $report->setClosedBy($report->getCreatedBy());

        try {
            $report->setClosedAt(new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga")));
        } catch (\Exception $e) {
        }

        $this->reportRepository->save($report, true);

        return new JsonResponse([
            "message" => "Это обращение в службу поддержки успешно деактивировано."
        ], 200);
    }
}