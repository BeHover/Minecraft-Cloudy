<?php

declare(strict_types=1);

namespace App\Domain;

use App\Repository\Main\ReportRepository;

class ReportsDataResolver
{
    private ReportRepository $reportRepository;

    public function __construct(
        ReportRepository $reportRepository
    )
    {
        $this->reportRepository = $reportRepository;
    }

    public function getAllReports(): array
    {
        $data = [];
        $reports = $this->reportRepository->findAll();

        foreach ($reports as $report) {
            $data[] = $this->getReportById($report->getId());
        }

        return $data;
    }

    public function getReportById(int $reportId): array
    {
        $data = [];
        $report = $this->reportRepository->findOneBy(["id" => $reportId]);

        if ($report === null) return $data;

        $data["id"] = $report->getId();

        $data["reporter"] = [
            "id" => $report->getReporter()->getId(),
            "username" => $report->getReporter()->getUsername(),
        ];

        $data["type"] = [
            "id" => $report->getType()->getId(),
            "name" => $report->getType()->getName()
        ];

        $data["status"] = $report->getStatus();
        $data["text"] = $report->getText();
        $data["response"] = $report->getResponse();
        $data["proofs"] = $report->getProofs();
        $data["createdAt"] = $report->getCreatedAt();
        $data["updatedAt"] = $report->getUpdatedAt();

        return $data;
    }
}