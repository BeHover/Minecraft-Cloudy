<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ReportTypeManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/reports/types")]
class ReportTypeController extends AbstractController
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

    #[Route("/create", name: "create_report_type", methods: ["POST"])]
    public function createReportType(Request $plainRequest) : JsonResponse {
        $requestData = $plainRequest->toArray();
        $name = $requestData["name"] ?? null;
        $locale = $plainRequest->query->get("locale", "en_EN");

        return $this->reportTypeManagementService->createReportType($name, $locale);
    }

    #[Route("/delete", name: "delete_report_type", methods: ["DELETE"])]
    public function deleteReportType(Request $plainRequest) : JsonResponse {
        $uuid = $plainRequest->query->get("uuid");
        $locale = $plainRequest->query->get("locale", "en_EN");

        return $this->reportTypeManagementService->deleteReportType($uuid, $locale);
    }
}