<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\LandsDataResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/lands')]
class LandsController extends AbstractController
{
    #[Route('', name: 'all_lands', methods: ['GET'])]
    public function getAllLands(
        LandsDataResolver $landsDataResolver
    ) : JsonResponse {
        $data = $landsDataResolver->getAllLands();

        return new JsonResponse($data);
    }
}
