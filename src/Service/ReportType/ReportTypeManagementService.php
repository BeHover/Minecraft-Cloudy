<?php

declare(strict_types=1);

namespace App\Service\ReportType;

use App\DTO\ReportType\ReportTypeDTO;
use App\Entity\Main\ReportType;
use App\Repository\Main\ReportTypeRepository;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Rfc4122\UuidV6;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReportTypeManagementService
{
    public function __construct(
        private readonly ReportTypeRepository $reportTypeRepository,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function getReportTypes(string $locale) : JsonResponse
    {
        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->reportTypeRepository->findAll();
        $types = [];

        if (null === $data) {
            $message = $this->translator->trans("report.types.empty", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        foreach ($data as $type) {
            $types[] = new ReportTypeDTO(
                id: $type->getId(),
                name: $type->getName(),
                createdAt: $type->getCreatedAt()
            );
        }

        return new JsonResponse($types);
    }

    public function createReportType(?string $name, string $locale): JsonResponse
    {
        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        if (null === $name) {
            $message = $this->translator->trans("report.types.create.name.empty", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        if (null !== $this->reportTypeRepository->findOneBy(["name" => $name])) {
            $message = $this->translator->trans("report.types.create.name.used", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $type = new ReportType($name);
        $this->reportTypeRepository->save($type, true);

        $message = $this->translator->trans("report.types.create.successfully", locale: $locale);
        return new JsonResponse(["message" => $message], Response::HTTP_CREATED);
    }

    public function deleteReportType(?string $uuid, string $locale): JsonResponse
    {
        if ($locale !== "en_EN" && $locale !== "ru_RU") {
            return new JsonResponse([
                "message" => "The selected language is not supported by the application."
            ], Response::HTTP_BAD_REQUEST);
        }

        if (null === $uuid) {
            $message = $this->translator->trans("report.types.uuid.empty", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        try {
            $typeUuid = UuidV6::fromString($uuid);
        } catch (InvalidUuidStringException $exception) {
            $message = $this->translator->trans("report.types.uuid.invalid", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $type = $this->reportTypeRepository->findOneBy(["id" => $typeUuid]);

        if (null === $type) {
            $message = $this->translator->trans("report.types.not_found", locale: $locale);
            return new JsonResponse(["message" => $message], Response::HTTP_BAD_REQUEST);
        }

        $this->reportTypeRepository->remove($type, true);

        $message = $this->translator->trans("report.types.delete.successfully", locale: $locale);
        return new JsonResponse(["message" => $message], Response::HTTP_CREATED);
    }
}