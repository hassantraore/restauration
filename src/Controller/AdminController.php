<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\DrinkRepository;
use App\Repository\ExtrasRepository;
use App\Repository\IngredientRepository;
use App\Repository\OrderRepository;
use App\Repository\PlatRepository;
use App\Repository\ReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_admin_dashboard');
    }

    #[Route('/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/categories', name: 'app_admin_categories')]
    public function categories(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categoryRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/plats', name: 'app_admin_products')]
    public function plats(PlatRepository $productRepository): Response
    {
        return $this->render('admin/plats/index.html.twig', [
            'plats' => $productRepository->findAll(),
        ]);
    }

    #[Route('/drinks', name: 'app_admin_drinks')]
    public function drinks(DrinkRepository $drinkRepository): Response
    {
        return $this->render('admin/drinks/index.html.twig', [
            'drinks' => $drinkRepository->findAll(),
        ]);
    }

    #[Route('/extras', name: 'app_admin_extras', methods: ['GET'])]
    public function extras(ExtrasRepository $extrasRepository): Response
    {
        return $this->render('admin/extras/index.html.twig', [
            'extras' => $extrasRepository->findAll(),
        ]);
    }

    #[Route('/ingredient', name: 'app_admin_ingredient', methods: ['GET'])]
    public function ingredient(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('admin/ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findAll(),
        ]);
    }

    #[Route('/order', name: 'app_admin_order', methods: ['GET'])]
    public function order(OrderRepository $orderRepository): Response
    {
        return $this->render('admin/order/index.html.twig', [
            'orders' => $orderRepository->findBy([], [
                'createdAt' => 'DESC',
            ]),
        ]);
    }

    #[Route('/report', name: 'app_admin_report', methods: ['GET'])]
    public function report(ReportRepository $reportRepository): Response
    {
        return $this->render('admin/report/index.html.twig', [
            'reports' => $reportRepository->findAll(),
        ]);
    }
}
