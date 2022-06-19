<?php

namespace App\Service;

use App\Entity\Wishlist;
use App\Repository\DrinkRepository;
use App\Repository\ExtrasRepository;
use App\Repository\PlatRepository;
use App\Repository\WishlistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class WishlistService
{
    private RequestStack $session;
    private PlatRepository $plat;
    private DrinkRepository $drink;
    private ExtrasRepository $extras;
    private Security $security;
    private WishlistRepository $wishlistRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(RequestStack $session, PlatRepository $plat, DrinkRepository $drink, ExtrasRepository $extras, Security $security, WishlistRepository $wishlistRepository, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->plat = $plat;
        $this->drink = $drink;
        $this->extras = $extras;
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->wishlistRepository = $wishlistRepository;
    }

    public function add($id, $type)
    {
        $wishlist = $this->session->getSession()->get('wishlist', []);

        if (empty($wishlist[$id])) {
            $wishlist[$id] = $type;
        }

        $this->session->getSession()->set('wishlist', $wishlist);
        $this->session->getSession()->set('totalWish', $this->getTotalWish());
        if ($this->security->getUser()) {
            $this->updateWishlist();
        }
    }

    public function sub($id)
    {
        $wishlist = $this->session->getSession()->get('wishlist', []);

        if (!empty($wishlist[$id])) {
            unset($wishlist[$id]);
        }

        $this->session->getSession()->set('wishlist', $wishlist);
        $this->session->getSession()->set('totalWish', $this->getTotalWish());
        if ($this->security->getUser()) {
            $this->updateWishlist();
        }
    }

    public function getWishlist()
    {
        $wishlist = $this->session->getSession()->get('wishlist', []);
        $wishlistData = [];
        foreach ($wishlist as $id => $type) {
            switch ($type) {
                case 'plat':
                    $entity = $this->plat->find($id);
                    break;
                case 'boisson':
                    $entity = $this->drink->find($id);
                    break;
                case 'extras':
                    $entity = $this->extras->find($id);
                    break;
            }
            $wishlistData[] = ['item' => $entity, 'type' => $type];
        }

        return $wishlistData;
    }

    public function setWishlist()
    {
        $wishlist = $this->updateWishlist();

        $this->session->getSession()->set('wishlist', $wishlist);
        $this->session->getSession()->set('totalWish', $this->getTotalWish());
    }

    public function getTotalWish()
    {
        $wishlist = $this->session->getSession()->get('wishlist', []);

        return count($wishlist);
    }

    public function updateWishlist(): array
    {
        $userWhish = $this->wishlistRepository->findOneBy([
            'user' => $this->security->getUser(),
        ]);

        $sessionWishlist = $this->session->getSession()->get('wishlist', []);

        if ($userWhish) {
            $wishlist = $userWhish->getItem() ? $userWhish->getItem() : [];
        } else {
            $wishlist = [];
        }

        if (!empty($sessionWishlist)) {
            if (!empty($wishlist)) {
                foreach ($sessionWishlist as $id => $type) {
                    if (empty($wishlist[$id])) {
                        $wishlist[$id] = $type;
                    }
                }
            } else {
                $wishlist = $sessionWishlist;
            }
        }

        if (!$userWhish) {
            $userWhish = new Wishlist();
            $userWhish->setUser($this->security->getUser());
            $userWhish->setItem($wishlist);
        } else {
            $userWhish->setItem($wishlist);
        }

        $this->entityManager->persist($userWhish);
        $this->entityManager->flush();

        return $wishlist;
    }
}
