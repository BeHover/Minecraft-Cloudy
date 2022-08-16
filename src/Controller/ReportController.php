<?php

namespace App\Controller;

use App\Entity\Main\Report;
use App\Repository\Main\ReportRepository;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

class ReportController extends AbstractController
{
    public function faq(
        string $page
    ) : Response
    {
        return $this->render(
            "pages/support/faq.html.twig",
            [
                "page" => $page
            ]
        );
    }

    public function userReports(
        ReportRepository $reportRepository
    ) : Response
    {
        $user = $this->getUser();
        $reports = $reportRepository->findBy(["reporter" => $user], ["createdAt" => "DESC"]);

        if (!$this->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute('login');
        }

        return $this->render(
            "pages/support/user_reports.html.twig",
            [
                "reports" => $reports
            ]
        );
    }

    public function createReport(
        Request $request,
        SluggerInterface $slugger
    ): RedirectResponse|Response
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();

        if (!$this->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute('login');
        }

        $report = new Report();
        $form = $this->createForm("App\Form\ReportType", $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $report->setReporter($user);
            $imageNames = [];
            $images = $form->get("images")->getData();

            foreach ($images as $image)
            {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $fileName = $safeFilename . time() . "." . $image->guessExtension();

                $image->move(
                    $this->getParameter("app.reports.images.dir"),
                    $fileName
                );

                $imageNames[] = $fileName;
            }

            $report->setImages($imageNames);

            try {
                $report->setCreatedAt(new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga")));
            } catch (\Exception) {
                // DateTimeImmutable Exception
            }

            $entityManager->persist($report);
            $entityManager->flush();

            return $this->redirectToRoute("user_reports");
        }

        return $this->renderForm(
            "pages/support/create_report.html.twig",
            [
                "formCreateReport" => $form,
                "exception" => null
            ]
        );
    }

    public function userReportInfo(
        int $id,
        ReportRepository $reportRepository
    ) : Response
    {
        $user = $this->getUser();
        $report = $reportRepository->findOneBy(["id" => $id, "reporter" => $user]);

        if (!$this->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute('login');
        }

        if (null === $report) {
            return $this->redirectToRoute("user_reports");
        }

        return $this->render(
            "pages/support/report_info.html.twig",
            [
                "report" => $report
            ]
        );
    }

    public function userUpdateReport(
        int $id,
        Request $request,
        SluggerInterface $slugger,
        ReportRepository $reportRepository
    ) : Response {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();

        if (!$this->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute('login');
        }

        $report = $reportRepository->findOneBy(["id" => $id, "reporter" => $user]);

        if($report->getStatus() == 1) {
            return $this->redirectToRoute("user_reports");
        }

        if (null === $report) {
            return $this->redirectToRoute("user_reports");
        }

        $form = $this->createForm("App\Form\ReportType", $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            try {
                $report->setCreatedAt(new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga")));
            } catch (\Exception) {
                // DateTimeImmutable Exception
            }

            $report->setReporter($this->getUser());

            $imageNames = [];
            $images = $form->get("images")->getData();
            foreach ($images as $image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $fileName = $safeFilename . time() . "." . $image->guessExtension();

                $image->move(
                    $this->getParameter("app.reports.images.dir"),
                    $fileName
                );

                $imageNames[] = $fileName;
            }
            $report->setImages($imageNames);

            $entityManager->persist($report);
            $entityManager->flush();

            return $this->redirectToRoute("user_report_info", ["id" => $report->getId()]);
        }

        return $this->renderForm(
            "pages/support/update_report.html.twig",
            [
                "report" => $report,
                "formUpdateReport" => $form,
                "exception" => null
            ]
        );
    }

    public function userDeleteReport(
        int $id,
        ReportRepository $reportRepository,
        EntityManagerInterface $entityManager
    ) : Response {
        $user = $this->getUser();
        $report = $reportRepository->findOneBy(["id" => $id, "reporter" => $user]);

        if (!$this->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute('login');
        }

        if (null === $report) {
            return $this->redirectToRoute("user_reports");
        }

        $entityManager->remove($report);
        $entityManager->flush();

        return $this->redirectToRoute("user_reports");
    }
}