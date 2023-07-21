<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Main\OTP;
use App\Entity\Main\User;
use App\Repository\Main\OTPRepository;

class OTPService
{
    private OTPRepository $OTPRepository;

    public function __construct(
        OTPRepository $OTPRepository
    )
    {
        $this->OTPRepository = $OTPRepository;
    }

    public function generateOTP(User $user): OTP
    {
        $data = $this->OTPRepository->findBy(["user" => $user]);

        if ($data !== null) {
            foreach ($data as $item) {
                $this->OTPRepository->remove($item, true);
            }
        }

        $code = new OTP(mt_rand(100000, 999999), $user);
        $this->OTPRepository->save($code, true);

        return $code;
    }
}