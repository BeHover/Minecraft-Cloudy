<?php

namespace App\Controller;

use App\Form\ReportResponseFormType;
use App\Repository\Main\ModeratorRepository;
use App\Repository\Main\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    public function main(): Response {
        return $this->render("pages/admin/main.html.twig");
    }

    public function reports(
        ReportRepository $reportRepository,
        ModeratorRepository $moderatorRepository
    ): Response {
        $moderator = $moderatorRepository->findOneBy(["user" => $this->getUser()]);

        if (null === $moderator) {
            return $this->redirectToRoute("admin");
        }

        $reports = $reportRepository->findBy(["server" => $moderator->getServer()], ["createdAt" => "DESC"]);

        return $this->render("pages/admin/reports.html.twig", ["reports" => $reports]);
    }

    public function viewReport(
        $id,
        Request $request,
        ReportRepository $reportRepository,
        EntityManagerInterface $entityManager
    ): RedirectResponse|Response
    {
        $report = $reportRepository->findOneBy(["id" => $id]);
        $formRespondReport = $this->createForm(ReportResponseFormType::class, $report);
        $formRespondReport->handleRequest($request);

        if (!$this->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute('login');
        }

        if ($formRespondReport->isSubmitted() && $formRespondReport->isValid()) {
            $report->setStatus(1);
            $entityManager->flush();

            return $this->redirectToRoute("admin_reports");
        }

        return $this->renderForm(
            "pages/admin/view_report.html.twig",
            [
                "formRespondReport" => $formRespondReport,
                "report" => $report
            ]
        );
    }
}