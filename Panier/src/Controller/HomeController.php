<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Home',
        ]);
    }

    #[Route('/produit', name: 'produit')]
    public function produit(ProduitRepository $produit): Response
    {
        $data=$produit->findAll();
        return $this->render('home/produit.html.twig', [
            'controller_name' => 'Produit',
            'data' => $data
        ]); 
    }

}

