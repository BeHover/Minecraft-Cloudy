<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Main\OTP;
use App\Entity\Main\User;
use App\Repository\Main\OTPRepository;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class OTPService
{
    private OTPRepository $OTPRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        OTPRepository $OTPRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->OTPRepository = $OTPRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws Exception
     */
    public function generateOTP(User $user): OTP
    {
        $data = $this->OTPRepository->findBy(["username" => $user->getUsername()]);

        if ($data !== null) {
            foreach ($data as $item) {
                $this->entityManager->remove($item);
                $this->entityManager->flush();
            }
        }

        $code = new OTP();
        $code->setOTP(mt_rand(100000, 999999));
        $code->setUsername($user->getUsername());
        $code->setCreatedAt(new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga")));
        $this->entityManager->persist($code);
        $this->entityManager->flush();

        return $code;
    }
}