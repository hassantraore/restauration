<?php

namespace App\Controller;

use App\Entity\Report;
use App\Form\ReportType;
use App\Repository\OrderRepository;
use App\Repository\ReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/report')]
class ReportController extends AbstractController
{
    #[Route('/new', name: 'app_report_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReportRepository $reportRepository, OrderRepository $orderRepository): Response
    {
        $report = new Report();
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* dd($report); */
            $reportRepository->add($report, true);

            return $this->redirectToRoute('app_admin_report', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/report/actions/new.html.twig', [
            'report' => $report,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_report_show', methods: ['GET'])]
    public function show(Report $report): Response
    {
        return $this->render('admin/report/actions/show.html.twig', [
            'report' => $report,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_report_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Report $report, ReportRepository $reportRepository, OrderRepository $orderRepository): Response
    {
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reportRepository->add($report, true);

            return $this->redirectToRoute('app_admin_report', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/report/actions/edit.html.twig', [
            'report' => $report,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_report_delete', methods: ['POST'])]
    public function delete(Request $request, Report $report, ReportRepository $reportRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$report->getId(), $request->request->get('_token'))) {
            $reportRepository->remove($report, true);
        }

        return $this->redirectToRoute('app_admin_report', [], Response::HTTP_SEE_OTHER);
    }
}
