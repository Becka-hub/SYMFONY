<?php

namespace App\Service\Cart;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private SessionInterface $session;
    private ProduitRepository $produit;
    public function __construct(SessionInterface $session, ProduitRepository $produit)
    {
        $this->session = $session;
        $this->produit = $produit;
    }

    public function add(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        $this->session->set('panier', $panier);
    }
    public function remove(int $id)
    {
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $this->session->set('panier', $panier);
    }

    public function getFullCart(): array
    {
        $panier = $this->session->get('panier', []);
        $panierWithData = [];
        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'produit' => $this->produit->find($id),
                'quantity' => $quantity
            ];
        }

        return $panierWithData;
    }

    public function getTotal(): float
    {
        $total = 0;
        $panierWithData = $this->getFullCart();
        foreach ($panierWithData as $item) {
            $totalItem = $item['produit']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }
        return $total;
    }
}
