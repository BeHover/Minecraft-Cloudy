<?php

declare(strict_types=1);

namespace App\Controller\Lands;

use App\Entity\Main\User;
use App\Service\Lands\LandsManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/lands")]
class GetUserLandsController extends AbstractController
{
    public function __construct(
        private readonly LandsManagementService $landsService
    ) {
    }

    #[Route("/by-user", name: "get_lands_by_user", methods: ["GET"])]
    public function getLandsByUser(Request $plainRequest) : JsonResponse {
        $locale = $plainRequest->query->get("locale", "en_EN");

        /** @var User $user */
        $user = $this->getUser();

        return $this->landsService->getLandsByUser($user, $locale);
    }
}
