<?php

declare(strict_types=1);

namespace App\Service\Report;

use App\DTO\Report\ReportDTO;
use App\Entity\Main\Report;
use App\Entity\Main\ReportChatMessage;
use App\Entity\Main\User;
use App\Repository\Main\ReportChatMessageRepository;
use App\Repository\Main\ReportRepository;
use App\Repository\Main\ReportTypeRepository;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Rfc4122\UuidV6;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReportManagementService
{
    public function __construct(
        private readonly ReportRepository $reportRepository,
        private readonly ReportTypeRepository $reportTypeRepository,
        private readonly ReportChatMessageRepository $reportChatMessageRepository,
        private readonly ReportChatMessageManagementService $reportChatMessageManagementService,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function getReport(User $user, string $uuid, string $locale) : JsonResponse
    {
        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $reportUuid = UuidV6::fromString($uuid);
        } catch (InvalidUuidStringException $exception) {
            $message = $this->translator->trans("report.invalid", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->reportRepository->findOneBy(["id" => $reportUuid]);

        if (null === $data) {
            $message = $this->translator->trans("report.not_found", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        if ($data->getCreatedBy() !== $user && !in_array("ROLE_ADMIN", $user->getRoles())) {
            $message = $this->translator->trans("user.not_permission", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_UNAUTHORIZED);
        }

        $report = new ReportDTO(
            id: $data->getId(),
            createdBy: $data->getCreatedBy(),
            type: $data->getType(),
            text: $data->getText(),
            createdAt: $data->getCreatedAt(),
            closedAt: $data->getClosedAt(),
            closedBy: $data->getClosedBy()
        );

        $messages = $this->reportChatMessageManagementService->getMessages($data);

        return new JsonResponse([
            "report" => $report,
            "messages" => $messages
        ]);
    }

    public function getReports(User $user, string $locale) : JsonResponse
    {
        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!in_array("ROLE_ADMIN", $user->getRoles())) {
            $message = $this->translator->trans("user.not_permission", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_UNAUTHORIZED);
        }

        $data = $this->reportRepository->findBy(["createdBy" => $user]);
        $reports = [];

        if (null === $data) {
            $message = $this->translator->trans("report.empty", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        foreach ($data as $report) {
            $reports[] = [
                "report" => new ReportDTO(
                    id: $report->getId(),
                    createdBy: $report->getCreatedBy(),
                    type: $report->getType(),
                    text: $report->getText(),
                    createdAt: $report->getCreatedAt(),
                    closedAt: $report->getClosedAt(),
                    closedBy: $report->getClosedBy()
                ),
                "messages" => $this->reportChatMessageManagementService->getMessages($report)
            ];
        }

        return new JsonResponse($reports);
    }

    public function createReport(User $user, ?string $uuid, ?string $text, string $locale) : JsonResponse
    {
        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        if (null === $uuid) {
            $message = $this->translator->trans("report.types.uuid.empty", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        if (empty($text)) {
            $message = $this->translator->trans("report.create.empty_text", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        try {
            $typeUuid = UuidV6::fromString($uuid);
        } catch (InvalidUuidStringException $exception) {
            $message = $this->translator->trans("report.types.uuid.invalid", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $type = $this->reportTypeRepository->findOneBy(["id" => $typeUuid]);

        if (null === $type) {
            $message = $this->translator->trans("report.types.not_found", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $report = new Report($user, $type, $text);
        $this->reportRepository->save($report, true);

        $message = $this->translator->trans("report.create.successfully", locale: $locale);
        return new JsonResponse(["message" => $message], Response::HTTP_CREATED);
    }

    public function postMessage(User $user, ?string $uuid, ?string $text, string $locale) : JsonResponse
    {
        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        if (empty($text)) {
            $message = $this->translator->trans("report.messages.post.empty", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        if (null === $uuid) {
            $message = $this->translator->trans("report.types.uuid.empty", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        try {
            $reportUuid = UuidV6::fromString($uuid);
        } catch (InvalidUuidStringException $exception) {
            $message = $this->translator->trans("report.invalid", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $report = $this->reportRepository->findOneBy(["id" => $reportUuid]);

        if (null === $report) {
            $message = $this->translator->trans("report.not_found", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        if ($report->getCreatedBy() !== $user && !in_array("ROLE_ADMIN", $user->getRoles())) {
            $message = $this->translator->trans("user.not_permission", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $message = new ReportChatMessage($report, $user, $text);
        $this->reportChatMessageRepository->save($message, true);

        $message = $this->translator->trans("report.messages.post.successfully", locale: $locale);
        return new JsonResponse(["message" => $message], Response::HTTP_CREATED);
    }

    public function deactivateReport(User $user, ?string $uuid, string $locale) : JsonResponse
    {
        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        if (null === $uuid) {
            $message = $this->translator->trans("report.types.uuid.empty", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        try {
            $reportUuid = UuidV6::fromString($uuid);
        } catch (InvalidUuidStringException $exception) {
            $message = $this->translator->trans("report.invalid", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $report = $this->reportRepository->findOneBy(["id" => $reportUuid]);

        if (null === $report) {
            $message = $this->translator->trans("report.not_found", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        if ($report->getClosed()) {
            $message = $this->translator->trans("report.closed", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        if ($report->getCreatedBy() !== $user && !in_array("ROLE_ADMIN", $user->getRoles())) {
            $message = $this->translator->trans("user.not_permission", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $report->setClosed($user);
        $this->reportRepository->save($report, true);

        $message = $this->translator->trans("report.deactivate.successfully", locale: $locale);
        return new JsonResponse(["message" => $message]);
    }
}