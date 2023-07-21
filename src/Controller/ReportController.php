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
use App\Service\JWTTokenService;
use DateTimeImmutable;
use DateTimeZone;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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
    private TranslatorInterface $translator;

    public function __construct(
        ReportRepository $reportRepository,
        ReportChatMessageRepository $reportChatMessageRepository,
        ReportTypeRepository $reportTypeRepository,
        ReportsDataResolver $reportsDataResolver,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        JWTTokenService $JWTTokenService,
        TranslatorInterface $translator
    ) {
        $this->reportRepository = $reportRepository;
        $this->reportChatMessageRepository = $reportChatMessageRepository;
        $this->reportTypeRepository = $reportTypeRepository;
        $this->reportsDataResolver = $reportsDataResolver;
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->JWTTokenService = $JWTTokenService;
        $this->translator = $translator;
    }

    #[Route('', name: 'all_reports', methods: ['GET'])]
    public function getAllReports(
        Request $plainRequest
    ) : JsonResponse {
        $requestData = $plainRequest->toArray();
        $locale = $requestData["locale"] ?? "en_EN";

        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        /** @var User $user */
        $user = $this->getUser();

        if (!in_array("ROLE_ADMIN", $user->getRoles())) {
            $message = $this->translator->trans("report.get_all.not_permission", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_UNAUTHORIZED);
        }

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
    public function createReportType(Request $plainRequest) : JsonResponse {
        $requestData = $plainRequest->toArray();
        $name = $requestData["name"] ?? null;
        $locale = $requestData["locale"] ?? "en_EN";

        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        /** @var User $user */
        $user = $this->getUser();

        if ($name === null) {
            $message = $this->translator->trans("report.types.create.name.empty", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        if ($this->reportTypeRepository->findOneBy(["name" => $name]) !== null) {
            $message = $this->translator->trans("report.types.create.name.used", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $type = new ReportType($name);
        $this->reportTypeRepository->save($type, true);

        return new JsonResponse([
            "message" => "Новый тип обращения в службу поддержки успешно создан."
        ], Response::HTTP_CREATED);
    }

    #[Route('/create', name: 'create_report', methods: ['POST'])]
    public function createReport(Request $plainRequest) : JsonResponse {
        $requestData = $plainRequest->toArray();
        $typeUuid = $requestData["type"] ?? null;
        $text = $requestData["text"] ?? null;
        $locale = $requestData["locale"] ?? "en_EN";

        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        /** @var User $user */
        $user = $this->getUser();

        if ($typeUuid === null || $text === null) {
            $message = $this->translator->trans("report.create.empty_fields", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $type = $this->reportTypeRepository->findOneBy(["id" => $typeUuid]);

        if ($type === null) {
            $message = $this->translator->trans("report.create.type.not_found", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_NOT_FOUND);
        }

        $report = new Report($user, $type, $text);
        $this->reportRepository->save($report, true);

        $message = $this->translator->trans("report.create.successfully", locale: $locale);
        return new JsonResponse(["message" => $message], Response::HTTP_CREATED);
    }

    #[Route('/{reportId}', name: 'report_data', methods: ['GET'])]
    public function getReportById(Request $plainRequest, string $reportId) : JsonResponse {
        $requestData = $plainRequest->toArray();
        $locale = $requestData["locale"] ?? "en_EN";

        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        $reportUuid = Uuid::fromString($reportId);
        $report = $this->reportsDataResolver->getReportById((string) $reportUuid);

        if (empty($report)) {
            $message = $this->translator->trans("report.not_found", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($report);
    }

    #[Route('/{reportId}/message', name: 'post_message', methods: ['POST'])]
    public function postMessage(Request $plainRequest, string $reportId) : JsonResponse {
        $requestData = $plainRequest->toArray();
        $text = $requestData["message"] ?? null;
        $locale = $requestData["locale"] ?? "en_EN";

        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        /** @var User $user */
        $user = $this->getUser();

        if ($text === null) {
            $message = $this->translator->trans("report.messages.post.empty", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $reportUuid = Uuid::fromString($reportId);
        $report = $this->reportRepository->findOneBy(["id" => $reportUuid]);

        if (null === $report) {
            $message = $this->translator->trans("report.not_found", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_NOT_FOUND);
        }

        if ($report->getCreatedBy() !== $user || !in_array("ROLE_ADMIN", $user->getRoles())) {
            $message = $this->translator->trans("report.not_permission", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $message = new ReportChatMessage($report, $user, $text);
        $this->reportChatMessageRepository->save($message, true);

        $message = $this->translator->trans("report.not_permission", locale: $locale);
        return new JsonResponse(["message" => $message], Response::HTTP_CREATED);
    }

    #[Route('/{reportId}/deactivate', name: 'deactivate_report', methods: ['POST'])]
    public function deactivateReport(Request $plainRequest, string $reportId) : JsonResponse {
        $requestData = $plainRequest->toArray();
        $locale = $requestData["locale"] ?? "en_EN";

        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        /** @var User $user */
        $user = $this->getUser();

        $reportUuid = Uuid::fromString($reportId);
        $report = $this->reportRepository->findOneBy(["id" => $reportUuid]);

        if (null === $report) {
            $message = $this->translator->trans("report.not_found", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_NOT_FOUND);
        }

        if ($report->getCreatedBy() !== $user || !in_array("ROLE_ADMIN", $user->getRoles())) {
            $message = $this->translator->trans("report.not_permission", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $report->setStatus(0);
        $report->setClosedBy($report->getCreatedBy());

        try {
            $report->setClosedAt(new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga")));
        } catch (\Exception $e) {
        }

        $this->reportRepository->save($report, true);


        $message = $this->translator->trans("report.deactivate.successfully", locale: $locale);
        return new JsonResponse(["message" => $message]);
    }
}