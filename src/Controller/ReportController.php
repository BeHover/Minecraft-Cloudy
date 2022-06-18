<?php

namespace App\Controller;

use App\Entity\Main\Report;
use App\Form\ReportResponseFormType;
use App\Repository\Main\ModeratorRepository;
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
            "support/faq.html.twig",
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
            "support/user_reports.html.twig",
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
            $server = $form->get("server")->getData();

            if ($server->getStatus() == null)
            {
                return $this->renderForm(
                    "support/create_report.html.twig",
                    [
                        "formCreateReport" => $form,
                        "exception" => "<b>Ошибка!</b> Этот сервер на данный момент не работает."
                    ]
                );
            }

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
            } catch (\Exception $e) {
                // DateTimeImmutable Exception
            }

            $entityManager->persist($report);
            $entityManager->flush();

            return $this->redirectToRoute("user_reports");
        }

        return $this->renderForm(
            "support/create_report.html.twig",
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
            "support/report_info.html.twig",
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
            $server = $form->get("server")->getData();

            if ($server->getStatus() == null)
            {
                return $this->renderForm(
                    "support/update_report.html.twig",
                    [
                        "report" => $report,
                        "formUpdateReport" => $form,
                        "exception" => "<b>Ошибка!</b> Этот сервер на данный момент не работает."
                    ]
                );
            }

            $report->setServer($server);

            try {
                $report->setCreatedAt(new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga")));
            } catch (\Exception $e) {
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
            "support/update_report.html.twig",
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
        $reports = $reportRepository->findBy(["reporter" => $user], ["createdAt" => "DESC"]);
        $report = $reportRepository->findOneBy(["id" => $id, "reporter" => $user]);

        if (!$this->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute('login');
        }

        if (null === $report) {
            return $this->render("profile/my-reports.html.twig", [
                "reports" => $reports,
                "message" => null
            ]);
        }

        $entityManager->remove($report);
        $entityManager->flush();

        return $this->redirectToRoute("user_reports");
    }

    public function moderatorReports(
        ModeratorRepository $moderatorRepository,
        ReportRepository $reportRepository
    ): Response {
        $moderator = $moderatorRepository->findOneBy(["user" => $this->getUser()]);
        $reports = $reportRepository->findBy(["server" => $moderator->getServer()], ["createdAt" => "DESC"]);

        return $this->render(
            "moderator/reports.html.twig",
            [
                "reports" => $reports,
                "message" => null
            ]
        );
    }

    public function moderatorClosedReports(
        ModeratorRepository $moderatorRepository,
        ReportRepository $reportRepository
    ): Response {
        $moderator = $moderatorRepository->findOneBy(["user" => $this->getUser()]);
        $reports = $reportRepository->findBy(["server" => $moderator->getServer(), "status" => 1], ["createdAt" => "DESC"]);

        return $this->render(
            "moderator/reports.html.twig",
            [
                "reports" => $reports,
                "message" => null
            ]
        );
    }

    public function moderatorExpectedReports(
        ModeratorRepository $moderatorRepository,
        ReportRepository $reportRepository
    ): Response {
        $moderator = $moderatorRepository->findOneBy(["user" => $this->getUser()]);
        $reports = $reportRepository->findBy(["server" => $moderator->getServer(), "status" => 0], ["createdAt" => "DESC"]);

        return $this->render(
            "moderator/reports.html.twig",
            [
                "reports" => $reports,
                "message" => null
            ]
        );
    }

    public function moderatorRespondReport(
        $id,
        Request $request,
        ReportRepository $reportRepository,
        EntityManagerInterface $entityManager
    ): RedirectResponse|Response
    {
        $report = $reportRepository->findOneBy(["id" => $id]);
        $form = $this->createForm(ReportResponseFormType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $report->setStatus(1);
            $entityManager->flush();

            return $this->redirectToRoute("moderator_reports");
        }

        return $this->renderForm(
            "admin/respond_report.html.twig",
            [
                "form" => $form,
                "report" => $report,
                "message" => null
            ]
        );
    }

    public function moderatorDeleteReport(
        int $id,
        ReportRepository $reportRepository,
        EntityManagerInterface $entityManager,
        ModeratorRepository $moderatorRepository
    ) : Response {
        $moderator = $moderatorRepository->findOneBy(["user" => $this->getUser()]);
        $reports = $reportRepository->findBy(["server" => $moderator->getServer()], ["createdAt" => "DESC"]);
        $report = $reportRepository->findOneBy(["id" => $id]);

        if (null === $report) {
            return $this->render("moderator/reports.html.twig", [
                "reports" => $reports,
                "message" => null
            ]);
        }

        $entityManager->remove($report);
        $entityManager->flush();

        return $this->redirectToRoute("moderator_reports");
    }
}