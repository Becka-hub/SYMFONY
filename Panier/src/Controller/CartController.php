<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }


    #[Route('/panier', name: 'cart_panier')]
    public function panier(): Response
    {

        $panierWithData = $this->cartService->getFullCart();
        $total = $this->cartService->getTotal();
        return $this->render('home/panier.html.twig', [
            'controller_name' => 'Panier',
            'items' => $panierWithData,
            'total' => $total
        ]);
    }


    #[Route('/panier/add/{id}', name: 'cart_add')]
    public function add($id)
    {
        $this->cartService->add($id);
        return $this->redirectToRoute('cart_panier');
    }


    #[Route('/panier/remove/{id}', name: 'cart_remove')]
    public function remove($id)
    {
        $this->cartService->remove($id);
        return $this->redirectToRoute('cart_panier');
    }
}
