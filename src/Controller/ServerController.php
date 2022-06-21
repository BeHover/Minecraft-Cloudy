<?php

namespace App\Controller;

use App\Repository\Main\ServerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ServerController extends AbstractController
{
    public function servers(
        ServerRepository $serverRepository
    ): Response {
        $servers = $serverRepository->findAll();

        return $this->render(
            "pages/servers/list.html.twig",
            [
                "servers" => $servers,
                "message" => null
            ]
        );
    }

    public function serverRules(
        string $server_tag,
        ServerRepository $serverRepository
    ): Response {
        $server = $serverRepository->findOneBy(["tag" => $server_tag]);

        if (!$server) {
            return $this->redirectToRoute('our_servers');
        }

        return $this->render(
            "pages/servers/rules.html.twig",
            [
                "server" => $server
            ]
        );
    }
}