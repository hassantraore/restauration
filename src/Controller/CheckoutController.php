<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\AddressRepository;
use App\Service\CartService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/checkout')]
class CheckoutController extends AbstractController
{
    #[Route('/', name: 'app_checkout')]
    public function index(AddressRepository $addressRepository, CartService $cartService): Response
    {
        $cart = $cartService->getCart();
        if (count($cart) == 0) {
            return $this->redirectToRoute('app_cart');
        }
        $id = $this->getUser()->getId();
        $addresses = $addressRepository->findBy([
            'user' => $id,
        ]);

        $addresses_id = [];
        foreach ($addresses as $addresse) {
            $addresses_id[] = $addresse->getId();
        }

        return $this->render('checkout/index.html.twig', [
            'addresses' => $addresses,
            'addresses_id' => json_encode($addresses_id),
        ]);
    }

    #[Route('/payment/success', name: 'app_checkout_payment_success', methods: ['GET'])]
    public function paymentSuccess(RequestStack $session, CartService $cartService, EntityManagerInterface $entityManager, AddressRepository $addressRepository): Response
    {
        $checkout = $session->getSession()->get('checkout');
        if (!empty($checkout)) {
            $this->addOrder($checkout, true, $entityManager, $addressRepository, $cartService, $session);

            return $this->render('checkout/payment/success.html.twig');
        } else {
            return $this->redirectToRoute('app_checkout');
        }
    }

    #[Route('/payment/cancel', name: 'app_checkout_payment_cancel', methods: ['GET'])]
    public function paymentCancel(RequestStack $session): Response
    {
        $checkout = $session->getSession()->get('checkout');
        if (!empty($checkout)) {
            $session->getSession()->remove('checkout');

            return $this->render('checkout/payment/cancel.html.twig');
        } else {
            return $this->redirectToRoute('app_checkout');
        }
    }

    #[Route('/payment/{checkout}', name: 'app_checkout_payment', methods: ['POST', 'GET'])]
    public function payment($checkout, RequestStack $session, $stripeSK, CartService $cartService, EntityManagerInterface $entityManager, AddressRepository $addressRepository): Response
    {
        $checkout = json_decode($checkout);
        $payment_method = $checkout->method_payment;
        if ($payment_method == 'especes') {
            $this->addOrder($checkout, false, $entityManager, $addressRepository, $cartService, $session);

            return $this->redirectToRoute('app_account_order');
        } elseif ($payment_method == 'credit card') {
            $session->getSession()->set('checkout', $checkout);

            $items = [];
            foreach ($cartService->getCart() as $key => $value) {
                $items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $value['item']->getName(),
                    ],
                    'unit_amount' => $value['price'],
                ],
                'quantity' => $value['quantity'],
            ];
            }
            // Set your secret key. Remember to switch to your live secret key in production.
            // See your keys here: https://dashboard.stripe.com/apikeys
            //dd($cartService->getCart());
            Stripe::setApiKey($stripeSK);
            $payment = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $items,
            'shipping_options' => [
            [
                  'shipping_rate_data' => [
                    'type' => 'fixed_amount',
                    'fixed_amount' => [
                      'amount' => $checkout->shipping,
                      'currency' => 'eur',
                    ],
                    'display_name' => 'Frais de livraison',
                  ],
                ], ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_checkout_payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_checkout_payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
          ]);

            /* $payment_intent = \Stripe\PaymentIntent::create([
            'amount' => $session->getSession()->get('totalPrice') + $checkout->shipping,
            'currency' => 'eur',
            'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
            $output = [
                'clientSecret' => $payment_intent->client_secret,
            ]; */

            return $this->redirect($payment->url, 302);
        }
    }

    public function addOrder($checkout, $isPaid, EntityManagerInterface $entityManager, AddressRepository $addressRepository, CartService $cartService, RequestStack $session)
    {
        $totalPrice = $session->getSession()->get('totalPrice') + $checkout->shipping;
        $totalArticle = $session->getSession()->get('totalArticle');
        $payment_method = $checkout->method_payment;
        $order = new Order();
        $order->setItems($cartService->getCart());
        $order->setTotalArticle($totalArticle);
        $order->setIsPaid($isPaid);
        $order->setStatus('en cours');
        $order->setPaymentMethod($payment_method);
        $order->setShippingCost($checkout->shipping);
        $shipping = ['livraison_type' => $checkout->livraison];
        switch ($checkout->livraison) {
            case 'deliver':
                $shipping['addresse'] = $addressRepository->find($checkout->addresse);
                break;

            case 'take_away':
                $shipping['identifiant'] = [
                    'nom' => $checkout->identifiant->nom,
                    'tel' => $checkout->identifiant->tel,
                ];
                break;
        }
        $order->setCoordinate($shipping);
        $order->setTotalPrice($totalPrice);
        $order->setClient($this->getUser());
        $order->setCreatedAt(new DateTimeImmutable());
        $entityManager->persist($order);
        $entityManager->flush();
        $cartService->cleanCart();
    }
}
