<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Authme\Lands;
use App\Repository\Authme\AuthmeRepository;
use App\Repository\Authme\LandsRepository;
use App\Repository\Authme\LuckPermsPlayersRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class LandsManagementService
{
    public function __construct(
        private readonly LandsRepository $landsRepository,
        private readonly AuthmeRepository $authmeRepository,
        private readonly LuckPermsPlayersRepository $luckPermsPlayersRepository,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function getLand(string $landId, string $locale) : JsonResponse
    {
        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        $land = $this->landsRepository->findOneBy(["id" => $landId]);

        if (null === $land) {
            $message = $this->translator->trans("lands.not_found", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($this->getLandData($land));
    }

    public function getLands(string $locale) : JsonResponse
    {
        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        $lands = $this->landsRepository->findAll();

        if (null === $lands) {
            $message = $this->translator->trans("lands.empty", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $data = [];

        foreach ($lands as $land) {
            $data[] = $this->getLandData($land);
        }

        return new JsonResponse($data);
    }

    private function getLandData(Lands $land) : array
    {
        $area = json_decode($land->getArea(), true);
        $roles = $area["holder"]["roles"];
        $memberRoles = [];

        foreach ($area["holder"]["trusted"] as $memberConfig) {
            $parsed = explode(":", $memberConfig);
            $memberRoles[$parsed[0]] = $parsed[1];
        }

        $members = [];
        $memberIds = array_keys(json_decode($land->getMembers(), true));
        foreach ($memberIds as $memberId) {
            $memberData = [];
            $member = $this->luckPermsPlayersRepository->findOneBy(["uuid" => $memberId]);
            $memberData["username"] = $this->authmeRepository->findOneBy(["username" => $member->getUsername()])->getRealname();
            $role = $roles[$memberRoles[$memberId]]["name"];
            $memberData["role"] = preg_replace("/&\S/", "", $role);

            if ($roles[$memberRoles[$memberId]]["priority"] == 100001) {
                $owner = $memberData["username"];
            }

            $members[] = $memberData;
        }

        $spawn = json_decode($land->getSpawn(), true);
        if ($spawn !== null) {
            $location = [
                "x" => (int) $spawn["x"],
                "y" => (int) $spawn["y"],
                "z" => (int) $spawn["z"],
            ];
        }

        $stats = json_decode($land->getStats(), true);

        return [
            "id" => $land->getId(),
            "name" => preg_replace("/&\S/", "", $land->getName()),
            "title" => preg_replace("/&\S/", "", $land->getTitle()),
            "owner" => $owner,
            "type" => $land->getType(),
            "created" => date("d-m-Y, H:i:s", (int)($land->getCreated() / 1000)),
            "balance" => $land->getBalance(),
            "membersQuantity" => sizeof(array_keys(json_decode($land->getMembers(), true))),
            "members" => $members,
            "location" => $location,
            "stats" => $stats
        ];
    }
}