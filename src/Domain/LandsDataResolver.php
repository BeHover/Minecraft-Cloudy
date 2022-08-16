<?php

namespace App\Domain;

use App\Repository\Authme\AuthmeRepository;
use App\Repository\Authme\LandsRepository;
use App\Repository\Authme\PlayerRepository;

class LandsDataResolver
{
    private LandsRepository $landsRepository;
    private PlayerRepository $playerRepository;
    private AuthmeRepository $authmeRepository;

    public function __construct(
        LandsRepository $landsRepository,
        PlayerRepository $playerRepository,
        AuthmeRepository $authmeRepository
    )
    {
        $this->landsRepository = $landsRepository;
        $this->playerRepository = $playerRepository;
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
        $land = $this->landsRepository->findOneBy(['id' => $landId]);
        $landInfo = [];
        $spawn = json_decode($land->getSpawn(), true);

        $landInfo['id'] = $landId;
        $landInfo['type'] = $land->getType();
        $landInfo['name'] = preg_replace('/&\S/', '', $land->getName());
        $landInfo['balance'] = $land->getBalance();
        $landInfo['membersQuantity'] = sizeof(array_keys(json_decode($land->getMembers(), true)));
        $landInfo['createdAt'] = date('d.m.Y H:i', (int) $land->getCreated() / 1000);
        $landInfo['members'] = [];

        $landInfo['location'] = [
            'x' => $spawn['x'],
            'y' => $spawn['y'],
            'z' => $spawn['z'],
        ];

        $landInfo['stats'] = json_decode($land->getStats(), true);

        $area = json_decode($land->getArea(), true);
        $roles = $area['holder']['roles'];
        $memberRoles = [];

        foreach ($area['holder']['trusted'] as $memberConfig) {
            $parsed = explode(':', $memberConfig);
            $memberRoles[$parsed[0]] = $parsed[1];
        }

        $memberIds = array_keys(json_decode($land->getMembers(), true));
        foreach ($memberIds as $memberId) {
            $memberData = [];
            $member = $this->playerRepository->findOneBy(['player_uuid' => $memberId]);
            $memberData['username'] = $this->authmeRepository->findOneBy(['username' => $member->getPlayerName()])->getRealname();
            $role = $roles[$memberRoles[$memberId]]['name'];
            $memberData['role'] = preg_replace('/&\S/', '', $role);

            if ($roles[$memberRoles[$memberId]]['priority'] == 100001) {
                $landInfo['owner'] = $memberData['username'];
            }

            $landInfo['members'][] = $memberData;
        }

        return $landInfo;
    }
}