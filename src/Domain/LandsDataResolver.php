<?php

namespace App\Domain;

use App\Repository\Authme\AuthmeRepository;
use App\Repository\Authme\LandsRepository;
use App\Repository\Authme\PlayerStatsRepository;

class LandsDataResolver
{
    private LandsRepository $landsRepository;
    private PlayerStatsRepository $playerStatsRepository;
    private AuthmeRepository $authmeRepository;

    public function __construct(
        LandsRepository $landsRepository,
        PlayerStatsRepository $playerStatsRepository,
        AuthmeRepository $authmeRepository
    )
    {
        $this->landsRepository = $landsRepository;
        $this->playerStatsRepository = $playerStatsRepository;
        $this->authmeRepository = $authmeRepository;
    }

    public function getAllLands(string $server)
    {
        $data = [];
        $lands = $this->landsRepository->findAll();

        foreach ($lands as $land) {
            $data[] = $this->getLand($server, $land->getId());
        }

        return $data;
    }

    public function getLand(string $server, int $landId)
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
            $member = $this->playerStatsRepository->findOneBy(['uuid' => $memberId, 'server' => $server]);
            $memberData['username'] = $this->authmeRepository->findOneBy(['username' => $member->getName()])->getRealname();
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