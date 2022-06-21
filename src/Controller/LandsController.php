<?php

namespace App\Controller;

use App\Domain\LandsDataResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LandsController extends AbstractController
{
    public function getOne(
        $id,
        LandsDataResolver $dataResolver
    ): Response {
        return $this->render(
            'pages/lands/land.html.twig',
            [
                'land' => $dataResolver->getLand("civilization", $id)
            ]
        );
    }

    public function getAll(
        LandsDataResolver $dataResolver
    ) : Response {
        return $this->render(
            'pages/lands/index.html.twig',
            [
                'lands' => $dataResolver->getAllLands("civilization")
            ]
        );
    }
}
