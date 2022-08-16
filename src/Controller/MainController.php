<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    public function main(): Response {
        return $this->render("pages/main.html.twig");
    }

    public function rules(): Response {
        return $this->render("pages/rules.html.twig");
    }

    public function map(): RedirectResponse {
        return $this->redirect("http://95.216.92.82:25764/");
    }

    public function vk(): RedirectResponse {
        return $this->redirect("https://vk.com/minecraftcloudy");
    }

    public function discord(): RedirectResponse {
        return $this->redirect("https://discord.gg/UgdSMpg9Cz");
    }
}