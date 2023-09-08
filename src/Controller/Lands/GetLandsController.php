<?php

declare(strict_types=1);

namespace App\Controller\Lands;

use App\Service\Lands\LandsManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/lands")]
class GetLandsController extends AbstractController
{
    public function __construct(
        private readonly LandsManagementService $landsService
    ) {
    }

    #[Route("", name: "get_lands", methods: ["GET"])]
    public function getLands(Request $plainRequest) : JsonResponse {
        $locale = $plainRequest->query->get("locale", "en_EN");
        $id = $plainRequest->query->get("id");

        if (null !== $id) {
            return $this->landsService->getLand($id, $locale);
        }

        return $this->landsService->getLands($locale);
    }
}
