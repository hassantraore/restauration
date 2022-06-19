<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart')]
    public function index(CartService $cartService, RequestStack $session): Response
    {
        /* $session->getSession()->set('panier', []);
        $session->getSession()->set('totalArticle', 0); */
        /* dd($cartService->getCart(), $session->getSession()->get('panier', [])); */

        //dd($cartService->getCart());

        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getCart(),
            'totalPrice' => $cartService->getTotalPrice(),
        ]);
    }

    #[Route('/add/{item}', name: 'app_cart_add', methods: ['POST'])]
    public function add($item, CartService $cartService, RequestStack $session): Response
    {
        /*         dd(json_decode($item)); */
        $item = json_decode($item, true);
        $cartService->add($item['id'], $item['value'], $item['price'], $item['type'], $item['sauce']);
        //$this->addFlash('success', 'Le panier a ete mis a jour');

        return new JsonResponse([
            'cart' => $cartService->getCart(),
            'items' => $session->getSession()->get('panier', []),
            'totalArticle' => $cartService->getTotalArticle(),
            'totalPrice' => $cartService->getTotalPrice(),
            'message' => ['success', 'Le panier a bien ete mis a jour'],
        ]);
    }

    #[Route('/sub/{item}', name: 'app_sub_add', methods: ['POST'])]
    public function sub($item, CartService $cartService, RequestStack $session): Response
    {
        //dd(, $option);
        $item = json_decode($item, true);
        $cartService->sub($item['id'], $item['value'], $item['type'], $item['all']);
        //$this->addFlash('success', 'Le panier a ete mis a jour');

        return new JsonResponse([
            'totalPrice' => $cartService->getTotalPrice(),
            'cart' => $cartService->getCart(),
            'items' => $session->getSession()->get('panier', []),
            'totalArticle' => $cartService->getTotalArticle(),
            'message' => ['success', 'Le panier a bien ete mis a jour'],
        ]);
    }
}
