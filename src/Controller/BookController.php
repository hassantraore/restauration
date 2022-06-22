<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use DateTime;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(Request $request, ReservationRepository $reservationRepository, $stripeSK, RequestStack $session): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        try {
            $form->handleRequest($request);
        } catch (\Throwable $th) {
            $form->addError(new FormError($th));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $number_of_table = $reservation->getNumberOfPerson() % 4;
            $date_debut = $reservation->getDateDebut();
            $duration = $reservation->getDuration();

            $date_fin = date('Y-m-d H:i:s', strtotime($date_debut->format('Y-m-d H:i:s').' + '.$duration.' hours 900 seconds'));
            $_date_fin = new DateTime($date_fin);
            $reservation->setDateFin($_date_fin);

            $number_of_table = $number_of_table == 0 ? 1 : $number_of_table;
            $reservation->setNumberOfTable($number_of_table);

            $all_reservation = $reservationRepository->getReservationOfDate($date_debut, $date_fin);

            $error = false;
            if (!empty($all_reservation)) {
                $nb_table = 0;
                $plage_horaire = [];
                foreach ($all_reservation as $key => $value) {
                    $debut = $value->getDateDebut()->format('d-m-Y H:i');
                    $fin = $value->getDateFin()->format('d-m-Y H:i');
                    $plage_horaire[] = $debut.' - '.$fin;
                    $nb_table += $value->getNumberOfTable();
                }
                if (($nb_table + $number_of_table) > 3) {
                    $error = true;
                    $message = "Il n'y a pas de place disponible entre : ";
                    $message .= implode(' et ', $plage_horaire);
                    $message .= ' . Veuillez choisir une plage horaire différente .';
                }
            }

            if (!$error) {
                $session->getSession()->set('reservation', $reservation);
                $price = 20 * $number_of_table * $duration;
                Stripe::setApiKey($stripeSK);
                $payment = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => 'Reservation',
                            ],
                            'unit_amount' => $price,
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => $this->generateUrl('app_book_success', ['price' => $price], UrlGeneratorInterface::ABSOLUTE_URL),
                    'cancel_url' => $this->generateUrl('app_book_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
                ]);

                return $this->redirect($payment->url, 302);
            } else {
                $form->addError(new FormError($message));
            }
        }

        return $this->renderForm('book/index.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/book/success/{price}', name: 'app_book_success')]
    public function reservationSuccess($price, MailerInterface $mailer, RequestStack $session, ReservationRepository $reservationRepository)
    {
        $reservation = $session->getSession()->get('reservation');
        if (!empty($reservation)) {
            $reservation->setPrice($price);

            $email = (new TemplatedEmail())
            ->from(new Address('traorehassan5542@gmail.com', 'Af délices'))
            ->to($reservation->getEmail())
            ->subject('Confirmation de reservation')
            ->htmlTemplate('book/confirmation_email.html.twig');

            $context = $email->getContext();
            $email->context($context);

            $mailer->send($email);
            $reservationRepository->add($reservation, true);
            $session->getSession()->remove('reservation');

            return $this->render('book/payment/success.html.twig');
        } else {
            return $this->redirectToRoute('app_book');
        }
    }

    #[Route('/book/cancel', name: 'app_book_cancel')]
    public function reservationCancel(RequestStack $session)
    {
        $book = $session->getSession()->get('reservation');
        if (!empty($book)) {
            $session->getSession()->remove('reservation');

            return $this->render('book/payment/cancel.html.twig');
        } else {
            return $this->redirectToRoute('app_book');
        }
    }
}
