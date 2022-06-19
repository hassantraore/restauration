<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\DrinkRepository;
use App\Repository\ExtrasRepository;
use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(RequestStack $session, CategoryRepository $categoryRepository, PlatRepository $productRepository, DrinkRepository $drinkRepository, ExtrasRepository $extrasRepository): Response
    {
        $extras = $categoryRepository->findOneBy(['name' => 'Extras']);
        $boissons = $categoryRepository->findOneBy(['name' => 'Boissons']);

        return $this->render('menu/index.html.twig', [
            'categories' => $categoryRepository->catWithoutExtras(['Extras', 'Boissons']),
            'plats' => $productRepository->findAll(),
            'wishlist' => $session->getSession()->get('wishlist', []),
            'extras' => $extrasRepository->findAll(),
            'boissons' => $drinkRepository->findAll(),
        ]);
    }
}
