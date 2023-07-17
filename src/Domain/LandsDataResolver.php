<?php

namespace App\Domain;

use App\Repository\Authme\AuthmeRepository;
use App\Repository\Authme\LandsRepository;
use App\Repository\Authme\LuckPermsPlayersRepository;

class LandsDataResolver
{
    private LandsRepository $landsRepository;
    private LuckPermsPlayersRepository $luckPermsPlayersRepository;
    private AuthmeRepository $authmeRepository;

    public function __construct(
        LandsRepository            $landsRepository,
        LuckPermsPlayersRepository $luckPermsPlayersRepository,
        AuthmeRepository           $authmeRepository
    )
    {
        $this->landsRepository = $landsRepository;
        $this->luckPermsPlayersRepository = $luckPermsPlayersRepository;
        $this->authmeRepository = $authmeRepository;
    }

    public function getAllLands(): array
    {
        $data = [];
        $lands = $this->landsRepository->findAll();

        foreach ($lands as $land) {
            $data[] = $this->getLand($land->getId());
        }

        return $data;
    }

    public function getLand(int $landId): array
    {
        $land = $this->landsRepository->findOneBy(["id" => $landId]);
        $data = [];
        $spawn = json_decode($land->getSpawn(), true);

        $data["id"] = $landId;
        $data["type"] = $land->getType();
        $data["name"] = preg_replace("/&\S/", "", $land->getName());
        $data["balance"] = $land->getBalance();
        $data["title"] = preg_replace("/&\S/", "", $land->getTitle());
        $data["membersQuantity"] = sizeof(array_keys(json_decode($land->getMembers(), true)));
        $data["createdAt"] = date("d.m.Y", (int) $land->getCreated() / 1000);
        $data["members"] = [];

        if($spawn !== null) {
            $data["location"] = [
                "x" => $spawn["x"],
                "y" => $spawn["y"],
                "z" => $spawn["z"],
            ];
        }

        $data["stats"] = json_decode($land->getStats(), true);

        $area = json_decode($land->getArea(), true);
        $roles = $area["holder"]["roles"];
        $memberRoles = [];

        foreach ($area["holder"]["trusted"] as $memberConfig) {
            $parsed = explode(":", $memberConfig);
            $memberRoles[$parsed[0]] = $parsed[1];
        }

        $memberIds = array_keys(json_decode($land->getMembers(), true));
        foreach ($memberIds as $memberId) {
            $memberData = [];
            $member = $this->luckPermsPlayersRepository->findOneBy(["uuid" => $memberId]);
            $memberData["username"] = $this->authmeRepository->findOneBy(["username" => $member->getUsername()])->getRealname();
//            $memberData["username"] = $this->authmeRepository->findOneBy(["username" => "CHYZHOV"])->getRealname();
            $role = $roles[$memberRoles[$memberId]]["name"];
            $memberData["role"] = preg_replace("/&\S/", "", $role);

            if ($roles[$memberRoles[$memberId]]["priority"] == 100001) {
                $data["owner"] = $memberData["username"];
            }

            $data["members"][] = $memberData;
        }

        return $data;
    }
}