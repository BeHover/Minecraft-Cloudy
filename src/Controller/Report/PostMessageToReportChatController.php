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
class PostMessageToReportChatController extends AbstractController
{
    public function __construct(
        private readonly ReportManagementService $reportManagementService
    ) {
    }

    #[Route("/message", name: "post_message", methods: ["POST"])]
    public function postMessage(Request $plainRequest) : JsonResponse {
        $requestData = $plainRequest->toArray();
        $text = $requestData["message"] ?? null;
        $uuid = $plainRequest->query->get("uuid");
        $locale = $plainRequest->query->get("locale", "en_EN");

        /** @var User $user */
        $user = $this->getUser();

        return $this->reportManagementService->postMessage($user, $uuid, $text, $locale);
    }
}