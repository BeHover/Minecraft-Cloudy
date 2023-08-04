<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Main\User;
use App\Service\ReportManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/reports')]
class ReportController extends AbstractController
{
    public function __construct(
        private readonly ReportManagementService $reportManagementService
    ) {
    }

    #[Route('', name: 'get_reports', methods: ['GET'])]
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

    #[Route('/create', name: 'create_report', methods: ['POST'])]
    public function createReport(Request $plainRequest) : JsonResponse {
        $requestData = $plainRequest->toArray();
        $typeUuid = $requestData["type"] ?? null;
        $text = $requestData["text"] ?? null;
        $locale = $plainRequest->query->get("locale", "en_EN");

        /** @var User $user */
        $user = $this->getUser();

        return $this->reportManagementService->createReport($user, $typeUuid, $text, $locale);
    }

    #[Route('/message', name: 'post_message', methods: ['POST'])]
    public function postMessage(Request $plainRequest) : JsonResponse {
        $requestData = $plainRequest->toArray();
        $text = $requestData["message"] ?? null;
        $uuid = $plainRequest->query->get("uuid");
        $locale = $plainRequest->query->get("locale", "en_EN");

        /** @var User $user */
        $user = $this->getUser();

        return $this->reportManagementService->postMessage($user, $uuid, $text, $locale);
    }

    #[Route('/deactivate', name: 'deactivate_report', methods: ['GET'])]
    public function deactivateReport(Request $plainRequest) : JsonResponse {
        $uuid = $plainRequest->query->get("uuid");
        $locale = $plainRequest->query->get("locale", "en_EN");

        /** @var User $user */
        $user = $this->getUser();

        return $this->reportManagementService->deactivateReport($user, $uuid, $locale);
    }
}