<?php

declare(strict_types=1);

namespace App\Controller\ReportType;

use App\Service\ReportType\ReportTypeManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/reports/types")]
class DeleteReportTypeController extends AbstractController
{
     public function __construct(
         private readonly ReportTypeManagementService $reportTypeManagementService
     ) {
     }

    #[Route("/delete", name: "delete_report_type", methods: ["DELETE"])]
    public function deleteReportType(Request $plainRequest) : JsonResponse {
        $uuid = $plainRequest->query->get("uuid");
        $locale = $plainRequest->query->get("locale", "en_EN");

        return $this->reportTypeManagementService->deleteReportType($uuid, $locale);
    }
}