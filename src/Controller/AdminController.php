<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchType;
use App\Repository\CategoryRepository;
use App\Repository\DrinkRepository;
use App\Repository\ExtrasRepository;
use App\Repository\IngredientRepository;
use App\Repository\MonthRepository;
use App\Repository\OrderRepository;
use App\Repository\PlatRepository;
use App\Repository\ReportRepository;
use App\Repository\YearRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/dashboard', name: 'app_admin_dashboard', methods: ['GET'])]
    public function dashboard(Request $request, MonthRepository $monthRepository, YearRepository $yearRepository): Response
    {
        $data = new SearchData();

        $year = date('Y');
        $annee = $yearRepository->findOneBy([
            'label' => $year,
        ]);
        $data->year = $annee;
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        $report = $data->year->getReports();
        $result = [];
        $totalExploitationProduct = [];
        $totalExploitationCharge = [];
        $totalFinancialProduct = [];
        $totalFinancialCharge = [];
        $totalNoCurrentProduct = [];
        $totalNoCurrentCharge = [];
        foreach ($report as $key => $value) {
            $month = $value->getMonth()->getLabel();
            $result[$month]['exploitationResult'] = $value->getExploitationResult();
            $result[$month]['financialResult'] = $value->getFinancialResult();
            $result[$month]['currentResult'] = $value->getCurrentResult();
            $result[$month]['noCurrentResult'] = $value->getNoCurrentResult();
            $result[$month]['resultBeforeImpot'] = $value->getResultBeforeImpot();
            $result[$month]['resultNet'] = $value->getResultNet();
            $totalExploitationProduct[$month] = 0;
            foreach ($value->getExploitationProducts() as $key => $_value) {
                $totalExploitationProduct[$month] += $_value['totalPrice'];
            }
            $totalExploitationCharge[$month] = 0;
            foreach ($value->getExploitationCharge() as $key => $_value) {
                $totalExploitationCharge[$month] += $_value->getMount();
            }
            $totalFinancialProduct[$month] = 0;
            foreach ($value->getFinancialProduct() as $key => $_value) {
                $totalFinancialProduct[$month] += $_value->getMount();
            }
            $totalFinancialCharge[$month] = 0;
            foreach ($value->getFinancialCharge() as $key => $_value) {
                $totalFinancialCharge[$month] += $_value->getMount();
            }
            $totalNoCurrentProduct[$month] = 0;
            foreach ($value->getNoCurrentProduct() as $key => $_value) {
                $totalNoCurrentProduct[$month] += $_value->getMount();
            }
            $totalNoCurrentCharge[$month] = 0;
            foreach ($value->getNoCurrentCharge() as $key => $_value) {
                $totalNoCurrentCharge[$month] += $_value->getMount();
            }
        }
        if ($request->get('ajax') && $request->isXmlHttpRequest()) {
            return new JsonResponse([
                'report' => $this->renderView('/admin/dashboard/cpc.html.twig', [
                    'year' => $data->year,
                    'month' => $monthRepository->findAll(),
                    'totalExploitationProduct' => $totalExploitationProduct,
                    'totalExploitationCharge' => $totalExploitationCharge,
                    'totalFinancialProduct' => $totalFinancialProduct,
                    'totalFinancialCharge' => $totalFinancialCharge,
                    'totalNoCurrentProduct' => $totalNoCurrentProduct,
                    'totalNoCurrentCharge' => $totalNoCurrentCharge,
                    'cpc' => $result,
                ]),
                'cpc' => $result,
            ]);
        }

        return $this->render('admin/dashboard/index.html.twig', [
            'years' => $yearRepository->findAll(),
            'year' => $year,
            'month' => $monthRepository->findAll(),
            'totalExploitationProduct' => $totalExploitationProduct,
            'totalExploitationCharge' => $totalExploitationCharge,
            'totalFinancialProduct' => $totalFinancialProduct,
            'totalFinancialCharge' => $totalFinancialCharge,
            'totalNoCurrentProduct' => $totalNoCurrentProduct,
            'totalNoCurrentCharge' => $totalNoCurrentCharge,
            'cpc' => $result,
            'form' => $form->createView(),
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
