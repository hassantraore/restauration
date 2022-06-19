<?php

namespace App\Controller;

use App\Entity\Extras;
use App\Form\ExtrasType;
use App\Repository\ExtrasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/extras')]
class ExtrasController extends AbstractController
{
    #[Route('/new', name: 'app_extras_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ExtrasRepository $extrasRepository): Response
    {
        $extra = new Extras();
        $form = $this->createForm(ExtrasType::class, $extra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $extrasRepository->add($extra);

            return $this->redirectToRoute('app_admin_extras', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/extras/actions/new.html.twig', [
            'extra' => $extra,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_extras_show', methods: ['GET'])]
    public function show(Extras $extra): Response
    {
        return $this->render('admin/extras/actions/show.html.twig', [
            'extra' => $extra,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_extras_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Extras $extra, ExtrasRepository $extrasRepository): Response
    {
        $form = $this->createForm(ExtrasType::class, $extra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $extrasRepository->add($extra);

            return $this->redirectToRoute('app_admin_extras', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/extras/actions/edit.html.twig', [
            'extra' => $extra,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_extras_delete', methods: ['POST'])]
    public function delete(Request $request, Extras $extra, ExtrasRepository $extrasRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$extra->getId(), $request->request->get('_token'))) {
            $extrasRepository->remove($extra);
        }

        return $this->redirectToRoute('app_admin_extras', [], Response::HTTP_SEE_OTHER);
    }
}
