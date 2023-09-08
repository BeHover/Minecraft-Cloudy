<?php

declare(strict_types=1);

namespace App\Controller\Report;

use App\Entity\Main\User;
use App\Service\Report\ReportManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/reports")]
class CreateReportController extends AbstractController
{
    public function __construct(
        private readonly ReportManagementService $reportManagementService
    ) {
    }

    #[Route("/create", name: "create_report", methods: ["POST"])]
    public function createReport(Request $plainRequest) : JsonResponse {
        $requestData = $plainRequest->toArray();
        $typeUuid = $requestData["type"] ?? null;
        $text = $requestData["text"] ?? null;
        $locale = $plainRequest->query->get("locale", "en_EN");

        /** @var User $user */
        $user = $this->getUser();

        return $this->reportManagementService->createReport($user, $typeUuid, $text, $locale);
    }
}