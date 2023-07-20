<?php

declare(strict_types=1);

namespace App\Domain;

use App\Repository\Main\ReportChatMessageRepository;
use App\Repository\Main\ReportRepository;
use Ramsey\Uuid\Uuid;

class ReportsDataResolver
{
    private ReportRepository $reportRepository;
    private ReportChatMessageRepository $reportChatMessageRepository;

    public function __construct(
        ReportRepository $reportRepository,
        ReportChatMessageRepository $reportChatMessageRepository
    )
    {
        $this->reportRepository = $reportRepository;
        $this->reportChatMessageRepository = $reportChatMessageRepository;
    }

    public function getAllReports(): array
    {
        $data = [];
        $reports = $this->reportRepository->findAll();

        foreach ($reports as $report) {
            $data[] = $this->getReportById((string) $report->getId());
        }

        return $data;
    }

    public function getReportById(string $reportId): array
    {
        $reportUuid = Uuid::fromString($reportId);

        $data = [];
        $report = $this->reportRepository->findOneBy(["id" => $reportUuid]);

        if ($report === null) return $data;

        $data["id"] = $report->getId();

        $data["createdBy"] = [
            "id" => $report->getCreatedBy()->getId(),
            "username" => $report->getCreatedBy()->getUsername(),
        ];

        $data["type"] = [
            "id" => $report->getType()->getId(),
            "name" => $report->getType()->getName()
        ];

        $data["isActive"] = $report->getStatus();
        $data["text"] = $report->getText();

        $data["messages"] = [];

        $messages = $this->reportChatMessageRepository->findBy(["report" => $report->getId()]);

        foreach ($messages as $message) {
            $data["messages"][] = [
                "id" => $message->getId(),
                "user" => $message->getUser()->getUsername(),
                "text" => $message->getText(),
                "createdAt" => $message->getCreatedAt()
            ];

        }

        $data["createdAt"] = $report->getCreatedAt();
        $data["closedAt"] = $report->getClosedAt();


        $report->getClosedBy() === null
            ? $data["closedBy"] = null
            : $data["closedBy"] = [
                "id" => $report->getClosedBy()->getId(),
                "username" => $report->getClosedBy()->getUsername(),
            ];

        return $data;
    }
}