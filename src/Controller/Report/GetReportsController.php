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
class GetReportsController extends AbstractController
{
    public function __construct(
        private readonly ReportManagementService $reportManagementService
    ) {
    }

    #[Route("", name: "get_reports", methods: ["GET"])]
    public function getReports(Request $plainRequest) : JsonResponse {
        $locale = $plainRequest->query->get("locale", "en_EN");
        $uuid = $plainRequest->query->get("uuid");

        /** @var User $user */
        $user = $this->getUser();

        if (null !== $uuid) {
            return $this->reportManagementService->getReport($user, $uuid, $locale);
        }

        return $this->reportManagementService->getReports($user, $locale);
    }
}