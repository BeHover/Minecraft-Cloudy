<?php

declare(strict_types=1);

namespace App\Service\Report;

use App\DTO\Report\ReportChatMessageDTO;
use App\Entity\Main\Report;
use App\Repository\Main\ReportChatMessageRepository;

class ReportChatMessageManagementService
{
    public function __construct(
        private readonly ReportChatMessageRepository $reportChatMessageRepository
    ) {
    }

    public function getMessages(Report $report) : array
    {
        $data = $this->reportChatMessageRepository->findBy(["report" => $report]);
        $messages = [];

        foreach ($data as $message) {
            $messages[] = new ReportChatMessageDTO(
                id: $message->getId(),
                report: $message->getReport(),
                user: $message->getUser(),
                text: $message->getText(),
                createdAt: $message->getCreatedAt()
            );
        }

        return $messages;
    }
}