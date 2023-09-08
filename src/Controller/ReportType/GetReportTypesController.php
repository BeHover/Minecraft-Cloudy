<?php

declare(strict_types=1);

namespace App\Controller\ReportType;

use App\Service\ReportType\ReportTypeManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/reports/types")]
class GetReportTypesController extends AbstractController
{
     public function __construct(
         private readonly ReportTypeManagementService $reportTypeManagementService
     ) {
     }

    #[Route("", name: "get_report_types", methods: ["GET"])]
    public function getReportTypes(Request $plainRequest) : JsonResponse {
        $locale = $plainRequest->query->get("locale", "en_EN");

        return $this->reportTypeManagementService->getReportTypes($locale);
    }
}