<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Report;
use App\Form\SearchType;
use App\Repository\CategoryRepository;
use App\Repository\DrinkRepository;
use App\Repository\ExtrasRepository;
use App\Repository\IngredientRepository;
use App\Repository\MonthRepository;
use App\Repository\OrderRepository;
use App\Repository\PlatRepository;
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
    public function dashboard(Request $request, MonthRepository $monthRepository, YearRepository $yearRepository, OrderRepository $orderRepository): Response
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

            $total = $this->getReportsTotal($value, $orderRepository);

            //impot
            $result[$month]['impot'] = $value->getImpot();

            // exploitation
            $totalExploitationProduct[$month] = $total['totalExploitationProduct'];
            $totalExploitationCharge[$month] = $total['totalExploitationCharge'];
            $result[$month]['exploitationResult'] = $total['resultExploitation'];

            //financial
            $totalFinancialProduct[$month] = $total['totalFinancialProduct'];
            $totalFinancialCharge[$month] = $total['totalFinancialCharge'];
            $result[$month]['financialResult'] = $total['resultFinancial'];

            //current
            $result[$month]['currentResult'] = $total['resultCurrent'];

            //no current
            $totalNoCurrentProduct[$month] = $total['totalNoCurrentProduct'];
            $totalNoCurrentCharge[$month] = $total['totalNoCurrentCharge'];
            $result[$month]['noCurrentResult'] = $total['resultNoCurrent'];

            //before impot
            $result[$month]['resultBeforeImpot'] = $total['resultBeforeImpot'];

            //net
            $result[$month]['resultNet'] = $total['resultNet'];
        }
        if ($request->get('ajax') && $request->isXmlHttpRequest()) {
            return new JsonResponse([
                'report' => $this->renderView('/admin/dashboard/cpc.html.twig', [
                    'year' => $data->year->getLabel(),
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
    public function report(YearRepository $yearRepository, OrderRepository $orderRepository): Response
    {
        $years = $yearRepository->findAll();
        $result = [];
        foreach ($years as $key => $year) {
            $year_label = $year->getLabel();
            $reports = $year->getReports();
            $result[$year_label] = [];
            foreach ($reports as $key => $report) {
                $total = $this->getReportsTotal($report, $orderRepository);

                $result[$year_label][] = [
                    'id' => $report->getId(),
                    'month' => $report->getMonth(),
                    'exploitationResult' => $total['resultExploitation'],
                    'financialResult' => $total['resultFinancial'],
                    'currentResult' => $total['resultCurrent'],
                    'noCurrentResult' => $total['resultNoCurrent'],
                    'resultBeforeImpot' => $total['resultBeforeImpot'],
                    'impot' => $report->getImpot(),
                    'resultNet' => $total['resultNet'],
                ];
            }
        }

        return $this->render('admin/report/index.html.twig', [
            'years' => $years,
            'reports' => $result,
        ]);
    }

    public function getReportsTotal(Report $report, OrderRepository $orderRepository)
    {
        $return = [];

        $mois = $report->getMonth()->getId();
        $annee = $report->getYear()->getLabel();
        $order = $orderRepository->getExploitationProducts($mois, $annee);

        //exploitation
        $return['totalExploitationProduct'] = 0;
        foreach ($order as $key => $_value) {
            if ($_value->getIsPaid()) {
                $return['totalExploitationProduct'] += $_value->getTotalPrice();
            }
        }
        $return['totalExploitationCharge'] = 0;
        foreach ($report->getExploitationCharge() as $key => $_value) {
            $return['totalExploitationCharge'] += $_value->getMount();
        }
        $return['resultExploitation'] = $return['totalExploitationProduct'] - $return['totalExploitationCharge'];

        //financial
        $return['totalFinancialProduct'] = 0;
        foreach ($report->getFinancialProduct() as $key => $_value) {
            $return['totalFinancialProduct'] += $_value->getMount();
        }
        $return['totalFinancialCharge'] = 0;
        foreach ($report->getFinancialCharge() as $key => $_value) {
            $return['totalFinancialCharge'] += $_value->getMount();
        }
        $return['resultFinancial'] = $return['totalFinancialProduct'] - $return['totalFinancialCharge'];

        //current
        $return['resultCurrent'] = $return['resultFinancial'] + $return['resultExploitation'];

        //no current
        $return['totalNoCurrentProduct'] = 0;
        foreach ($report->getNoCurrentProduct() as $key => $_value) {
            $return['totalNoCurrentProduct'] += $_value->getMount();
        }
        $return['totalNoCurrentCharge'] = 0;
        foreach ($report->getNoCurrentCharge() as $key => $_value) {
            $return['totalNoCurrentCharge'] += $_value->getMount();
        }
        $return['resultNoCurrent'] = $return['totalNoCurrentProduct'] - $return['totalNoCurrentCharge'];

        //beforeImpot
        $return['resultBeforeImpot'] = $return['resultNoCurrent'] + $return['resultCurrent'];

        //net
        $return['resultNet'] = $return['resultBeforeImpot'] - $report->getImpot();

        return $return;
    }
}
