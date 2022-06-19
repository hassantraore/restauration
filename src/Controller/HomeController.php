<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(CartService $cartService, RequestStack $session): Response
    {
        return $this->render('home/index.html.twig', [
            'items' => json_encode($session->getSession()->get('panier', [])),
            'totalArticle' => $cartService->getTotalArticle(),
        ]);
    }
}
