<?php

declare(strict_types=1);

namespace App\Controller\ReportType;

use App\Service\ReportType\ReportTypeManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/reports/types")]
class CreateReportTypeController extends AbstractController
{
     public function __construct(
         private readonly ReportTypeManagementService $reportTypeManagementService
     ) {
     }

    #[Route("/create", name: "create_report_type", methods: ["POST"])]
    public function createReportType(Request $plainRequest) : JsonResponse {
        $requestData = $plainRequest->toArray();
        $name = $requestData["name"] ?? null;
        $locale = $plainRequest->query->get("locale", "en_EN");

        return $this->reportTypeManagementService->createReportType($name, $locale);
    }
}