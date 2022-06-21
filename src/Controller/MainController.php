<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    public function main(): Response {
        return $this->render(
            "pages/main.html.twig",
            [
                "message" => null
            ]
        );
    }

    public function vk(): RedirectResponse {
        return $this->redirect("https://vk.com/minecraftcloudy");
    }

    public function discord(): RedirectResponse {
        return $this->redirect("https://discord.gg/UgdSMpg9Cz");
    }

    public function mapCivilization(): RedirectResponse {
        return $this->redirect("http://135.181.237.35:25776/");
    }

    public function mapPhoenix(): RedirectResponse {
        return $this->redirect("http://95.216.92.82:25764/");
    }
}