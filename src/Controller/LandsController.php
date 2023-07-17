<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\LandsDataResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class LandsController extends AbstractController
{
    /**
     * @Route(path="/api/lands", name="lands", methods={"GET"})
     */
    public function lands(
        LandsDataResolver $landsDataResolver
    ) : JsonResponse {
        header("Access-Control-Allow-Origin: *");
        $data = $landsDataResolver->getAllLands();

        return new JsonResponse($data);
    }
}
