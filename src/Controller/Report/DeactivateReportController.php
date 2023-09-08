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
class DeactivateReportController extends AbstractController
{
    public function __construct(
        private readonly ReportManagementService $reportManagementService
    ) {
    }

    #[Route("/deactivate", name: "deactivate_report", methods: ["GET"])]
    public function deactivateReport(Request $plainRequest) : JsonResponse {
        $uuid = $plainRequest->query->get("uuid");
        $locale = $plainRequest->query->get("locale", "en_EN");

        /** @var User $user */
        $user = $this->getUser();

        return $this->reportManagementService->deactivateReport($user, $uuid, $locale);
    }
}