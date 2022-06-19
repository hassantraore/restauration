<?php

namespace App\Service;

use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Repository\DrinkRepository;
use App\Repository\ExtrasRepository;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CartService
{
    private RequestStack $session;
    private PlatRepository $plat;
    private DrinkRepository $drink;
    private ExtrasRepository $extras;
    private Security $security;
    private CartRepository $cartRepository;
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializerInterface;
    private $encoder;
    private $defaultContext;
    private $normalizer;
    private $serializer;

    public function __construct(RequestStack $session, PlatRepository $plat, DrinkRepository $drink, ExtrasRepository $extras, Security $security, CartRepository $cartRepository, EntityManagerInterface $entityManager, SerializerInterface $serializerInterface)
    {
        $this->session = $session;
        $this->plat = $plat;
        $this->drink = $drink;
        $this->extras = $extras;
        $this->security = $security;
        $this->cartRepository = $cartRepository;
        $this->entityManager = $entityManager;
        $this->serializerInterface = $serializerInterface;
        $this->encoder = new JsonEncoder();
        $this->defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->toArray();
            },
        ];
        $this->normalizer = new ObjectNormalizer(null, null, null, null, null, null, $this->defaultContext);
        $this->serializer = new Serializer([$this->normalizer], [$this->encoder]);
    }

    public function add($id, $value, int $price, $type = 'plat', $sauce = false)
    {
        $panier = $this->session->getSession()->get('panier', []);

        switch ($type) {
            case 'plat':
                if (empty($panier[$type])) {
                    $panier[$type] = [];
                }
                if (empty($panier[$type][$id])) {
                    $panier[$type][$id] = [];
                }
                if (empty($panier[$type][$id])) {
                    $panier[$type][$id] = [];
                }
                if (empty($panier[$type][$id][$value])) {
                    $panier[$type][$id][$value] = [];
                }
                break;

            default:
                if (empty($panier[$type])) {
                    $panier[$type] = [];
                }
                if (empty($panier[$type][$value])) {
                    $panier[$type][$value] = [];
                }
                break;
        }

        if ($type == 'plat') {
            $panier[$type][$id][$value]['sauce'] = $sauce;

            if (empty($panier[$type][$id][$value]['quantity'])) {
                $panier[$type][$id][$value]['quantity'] = 1;
                $panier[$type][$id][$value]['price'] = $price;
            } else {
                ++$panier[$type][$id][$value]['quantity'];
            }
        } else {
            if (empty($panier[$type][$value]['quantity'])) {
                $panier[$type][$value]['quantity'] = 1;
                $panier[$type][$value]['price'] = $price;
            } else {
                ++$panier[$type][$value]['quantity'];
            }
        }

        $this->session->getSession()->set('panier', $panier);
        $this->session->getSession()->set('totalArticle', $this->getTotalArticle());
        $this->session->getSession()->set('totalPrice', $this->getTotalPrice());
        if ($this->security->getUser()) {
            $this->updateCart($panier);
        }
    }

    public function sub($id, $value, $type = 'plat', $all = false)
    {
        $panier = $this->session->getSession()->get('panier', []);
        if (count($panier) !== 0) {
            if ($type == 'plat') {
                if ($all) {
                    unset($panier[$type][$id][$value]);
                } else {
                    if (!empty($panier[$type][$id])) {
                        if (!empty($panier[$type][$id][$value])) {
                            if ($panier[$type][$id][$value]['quantity'] > 1) {
                                --$panier[$type][$id][$value]['quantity'];
                            /* dd($price); */
                            } else {
                                unset($panier[$type][$id][$value]);
                            }
                        }
                    }
                }
                if (!empty($panier[$type][$id]) && count($panier[$type][$id]) === 0) {
                    unset($panier[$type][$id]);
                }
            } else {
                if ($all) {
                    unset($panier[$type][$value]);
                } else {
                    if (!empty($panier[$type]) && !empty($panier[$type][$value]) && count($panier[$type][$value]) !== 0) {
                        if ($panier[$type][$value]['quantity'] > 1) {
                            --$panier[$type][$value]['quantity'];
                        } else {
                            unset($panier[$type][$value]);
                        }
                    }
                }
            }
        }
        /* foreach ($panier as $type => $value) {
            if (count($panier[$type]) === 0) {
                unset($panier[$type]);
            }
        } */

        $this->session->getSession()->set('panier', count($panier) !== 0 ? $panier : []);
        $this->session->getSession()->set('totalArticle', $this->getTotalArticle());
        $this->session->getSession()->set('totalPrice', $this->getTotalPrice());

        if ($this->security->getUser()) {
            $this->updateCart($panier);
        }
    }

    public function remove($id, $value, $type = 'plat')
    {
        $panier = $this->session->getSession()->get('panier', []);
        switch ($type) {
            case 'plat':
                unset($panier[$id][$value]);
                break;

            default:
                unset($panier[$value]);
                break;
        }

        $this->session->getSession()->set('panier', $panier);
        $this->session->getSession()->set('totalArticle', $this->getTotalArticle());
        $this->session->getSession()->set('totalPrice', $this->getTotalPrice());

        if ($this->security->getUser()) {
            $this->updateCart($panier);
        }
    }

    public function getCart(): array
    {
        $panier = $this->session->getSession()->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $type => $value) {
            foreach ($value as $id => $val) {
                switch ($type) {
                    case 'plat':
                        $item = $this->plat->find($id);
                        foreach ($val as $size => $_val) {
                            $panierWithData[] = [
                                'item' => $item,
                                'type' => $type,
                                'sauce' => $_val['sauce'],
                                'quantity' => $_val['quantity'],
                                'size' => $size,
                                'price' => $_val['price'],
                                'totalPrice' => $_val['price'] * $_val['quantity'],
                            ];
                        }
                        break;

                    default:
                        switch ($type) {
                            case 'boisson':
                                $item = $this->drink->find($id);
                                break;
                            case 'extras':
                                $item = $this->extras->find($id);
                                break;
                        }
                        $panierWithData[] = [
                            'item' => $item,
                            'type' => $type,
                            'quantity' => $val['quantity'],
                            'price' => $val['price'],
                            'totalPrice' => $val['price'] * $val['quantity'],
                        ];
                        break;
                }
            }
        }

        return $panierWithData;
    }

    public function setCart()
    {
        $panier = $this->updateCart();

        $this->session->getSession()->set('panier', $panier);
        $this->session->getSession()->set('totalArticle', $this->getTotalArticle());
        $this->session->getSession()->set('totalPrice', $this->getTotalPrice());
    }

    public function getTotalPrice(): float
    {
        $total = 0;

        foreach ($this->getCart() as $item) {
            $total += $item['totalPrice'];
        }

        return $total;
    }

    public function getTotalArticle(): int
    {
        $total = 0;

        foreach ($this->getCart() as $item) {
            $total += $item['quantity'];
        }

        return $total;
    }

    public function cleanCart()
    {
        $cart = $this->cartRepository->findOneBy([
            'user' => $this->security->getUser(),
        ]);
        $cart->setItem([]);
        $this->session->getSession()->clear();
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    public function updateCart($panier = null): array
    {
        $userCart = $this->cartRepository->findOneBy([
            'user' => $this->security->getUser(),
        ]);
        if ($panier == null) {
            $sessionCart = $this->session->getSession()->get('panier', []);
            if ($userCart) {
                $panier = $userCart->getItem() ? $userCart->getItem() : [];
            } else {
                $panier = [];
            }

            if (!empty($sessionCart)) {
                if (!empty($panier)) {
                    foreach ($sessionCart as $type => $value) {
                        if (!empty($panier[$type])) {
                            foreach ($value as $id => $val) {
                                if (!empty($panier[$type][$id])) {
                                    switch ($type) {
                                        case 'plat':
                                            foreach ($val as $size => $_val) {
                                                $panier[$type][$id][$size] = $_val;
                                            }
                                            break;
                                        default:
                                            $panier[$type][$id] = $val;
                                            break;
                                    }
                                } else {
                                    $panier[$type][$id] = $val;
                                }
                            }
                        } else {
                            $panier[$type] = $value;
                        }
                    }
                } else {
                    $panier = $sessionCart;
                }
            }

            /* if (!empty($params)) {
                if (!empty($panier[$params[0]]) && !empty($panier[$params[0]][$params[1]])) {
                    unset($panier[$params[0]][$params[1]]);
                }
            } */

            if (!$userCart) {
                $userCart = new Cart();
                $userCart->setUser($this->security->getUser());
                $userCart->setItem($panier);
            } else {
                $userCart->setItem($panier);
            }
        } else {
            $userCart->setItem($panier);
        }

        $this->entityManager->persist($userCart);
        $this->entityManager->flush();

        return $panier;
    }
}
