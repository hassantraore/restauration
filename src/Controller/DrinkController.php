<?php

namespace App\Controller;

use App\Entity\Drink;
use App\Form\DrinkType;
use App\Repository\DrinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/drinks')]
class DrinkController extends AbstractController
{
    #[Route('/new', name: 'app_drink_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DrinkRepository $drinkRepository): Response
    {
        $drink = new Drink();
        $form = $this->createForm(DrinkType::class, $drink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $drinkRepository->add($drink);

            return $this->redirectToRoute('app_admin_drinks', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('/admin/drinks/actions/new.html.twig', [
            'drink' => $drink,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_drink_show', methods: ['GET'])]
    public function show(Drink $drink): Response
    {
        return $this->render('/admin/drinks/actions/show.html.twig', [
            'drink' => $drink,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_drink_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Drink $drink, DrinkRepository $drinkRepository): Response
    {
        $form = $this->createForm(DrinkType::class, $drink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $drinkRepository->add($drink);

            return $this->redirectToRoute('app_admin_drinks', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('/admin/drinks/actions/edit.html.twig', [
            'drink' => $drink,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_drink_delete', methods: ['POST'])]
    public function delete(Request $request, Drink $drink, DrinkRepository $drinkRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$drink->getId(), $request->request->get('_token'))) {
            $drinkRepository->remove($drink);
        }

        return $this->redirectToRoute('app_admin_drinks', [], Response::HTTP_SEE_OTHER);
    }
}
