<?php

namespace App\Controller;

use App\Data\UserData;
use App\Form\UserType;
use App\Repository\AddressRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account')]
class AccountController extends AbstractController
{
    #[Route('/', name: 'app_account')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_account_dashboard');
    }

    #[Route('/dashboard', name: 'app_account_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('account/dashboard/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    #[Route('/fidelite', name: 'app_account_fidelite')]
    public function fidelite(): Response
    {
        return $this->render('account/fidelite/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    #[Route('/information', name: 'app_account_information')]
    public function information(HttpFoundationRequest $request): Response
    {
        $information = new UserData();
        $information->firstname = $this->getUser()->getFirstName();
        $information->lastname = $this->getUser()->getLastName();
        $form = $this->createForm(UserType::class, $information);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($information);
        }

        return $this->renderForm('account/information/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/address', name: 'app_account_address', methods: ['GET'])]
    public function address(AddressRepository $addressRepository): Response
    {
        return $this->render('account/address/index.html.twig', [
            'addresses' => $addressRepository->findBy([
                'user' => $this->getUser(),
            ]),
        ]);
    }

    #[Route('/order', name: 'app_account_order', methods: ['GET'])]
    public function order(OrderRepository $orderRepository): Response
    {
        return $this->render('account/order/index.html.twig', [
            'orders' => $orderRepository->findBy([
                'client' => $this->getUser(),
                'isActive' => 1,
            ], [
                'createdAt' => 'DESC',
            ]),
        ]);
    }
}
