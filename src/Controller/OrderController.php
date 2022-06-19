<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account/order')]
class OrderController extends AbstractController
{
    #[Route('/{id}', name: 'app_account_order_delete', methods: ['GET'])]
    public function delete(Order $order, EntityManagerInterface $entityManager): Response
    {
        $order->setIsActive(0);
        $entityManager->persist($order);
        $entityManager->flush();

        return $this->redirectToRoute('app_account_order');
    }

    #[Route('/status/{id}/{status}', name: 'app_account_order_edit_status', methods: ['POST'])]
    public function editStatus(Order $order, $status, EntityManagerInterface $entityManager): Response
    {
        $order->setStatus($status);
        $entityManager->persist($order);
        $entityManager->flush();

        return new JsonResponse([
            'message' => ['success', 'le status de la commande a bien été mis a jour'],
        ]);
    }

    #[Route('/payment/{id}/{payment}', name: 'app_account_order_edit_payment', methods: ['POST'])]
    public function editPayment(Order $order, $payment, EntityManagerInterface $entityManager): Response
    {
        $order->setIsPaid($payment);
        $entityManager->persist($order);
        $entityManager->flush();

        return new JsonResponse([
            'message' => ['success', 'l\'état de paiement de la commande a bien été mis a jour'],
        ]);
    }
}
