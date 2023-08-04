<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\LandsManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/lands')]
class LandsController extends AbstractController
{
    public function __construct(
        private readonly LandsManagementService $managementService
    ) {
    }

    #[Route('', name: 'all_lands', methods: ['GET'])]
    public function getAllLands(Request $plainRequest) : JsonResponse {
        $locale = $plainRequest->query->get("locale", "en_EN");
        $id = $plainRequest->query->get("id");

        if (null !== $id) {
            return $this->managementService->getLand($id, $locale);
        }

        return $this->managementService->getLands($locale);
    }
}
