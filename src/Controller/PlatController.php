<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Form\PlatType;
use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/plats')]
class PlatController extends AbstractController
{
    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlatRepository $productRepository): Response
    {
        $product = new Plat();
        $form = $this->createForm(PlatType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $all_price = [];
            foreach ($product->getSize() as $key => $size) {
                $all_price[] = $size->getNewPrice();
            }
            $product->setPrice(min($all_price));
            $productRepository->add($product);

            return $this->redirectToRoute('app_admin_products', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/plats/actions/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Plat $product): Response
    {
        return $this->render('admin/plats/actions/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plat $product, PlatRepository $productRepository): Response
    {
        $form = $this->createForm(PlatType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSize($product->getSize());
            $productRepository->add($product);

            return $this->redirectToRoute('app_admin_products', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/plats/actions/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Plat $product, PlatRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product);
        }

        return $this->redirectToRoute('app_admin_products', [], Response::HTTP_SEE_OTHER);
    }
}
