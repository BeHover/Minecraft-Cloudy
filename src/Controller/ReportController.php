<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\ReportsDataResolver;
use App\Repository\Main\ReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/reports')]
class ReportController extends AbstractController
{
    private ReportRepository $reportRepository;
    private ReportsDataResolver $reportsDataResolver;

    public function __construct(
        ReportRepository $reportRepository,
        ReportsDataResolver $reportsDataResolver
    ) {
        $this->reportRepository = $reportRepository;
        $this->reportsDataResolver = $reportsDataResolver;
    }

    #[Route('', name: 'all_reports', methods: ['GET'])]
    public function getAllReports() : JsonResponse {
        $reports = $this->reportsDataResolver->getAllReports();

        return new JsonResponse($reports);
    }

    #[Route('/{reportId}', name: 'report_data', methods: ['GET'])]
    public function getReportById(int $reportId) : JsonResponse {
        $report = $this->reportsDataResolver->getReportById($reportId);

        if (empty($report)) {
            return new JsonResponse(["message" => "Тикет с таким идентификатором не найден."]);
        }

        return new JsonResponse($report);
    }
}