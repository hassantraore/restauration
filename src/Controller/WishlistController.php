<?php

namespace App\Controller;

use App\Service\WishlistService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wishlist')]
class WishlistController extends AbstractController
{
    #[Route('/', name: 'app_wishlist')]
    public function index(WishlistService $wishlistService): Response
    {
        return $this->render('wishlist/index.html.twig', [
            'wishlist' => $wishlistService->getWishlist(),
        ]);
    }

    #[Route('/add/{item}', name: 'app_wishlist_add', methods: ['POST'])]
    public function add($item, WishlistService $wishlistService, RequestStack $session): Response
    {
        $item = json_decode($item, true);
        $wishlistService->add($item['id'], $item['type']);
        $this->addFlash('success', 'Votre liste d\'envie a été mis a jour');

        return new JsonResponse([
            'items' => $session->getSession()->get('wishlist', []),
            'totalWish' => $wishlistService->getTotalWish(),
            'message' => ['success', 'La liste d\'envie a bien ete mis a jour'],
        ]);
    }

    #[Route('/sub/{item}', name: 'app_wishlist_sub', methods: ['POST'])]
    public function sub($item, WishlistService $wishlistService, RequestStack $session): Response
    {
        $item = json_decode($item, true);
        $wishlistService->sub($item['id']);
        $this->addFlash('success', 'Votre liste d\'envie a été mis a jour');

        return new JsonResponse([
            'wishlist' => $wishlistService->getWishlist(),
            'items' => $session->getSession()->get('wishlist', []),
            'totalWish' => $wishlistService->getTotalWish(),
            'message' => ['success', 'La liste d\'envie a bien ete mis a jour'],
        ]);
    }
}
