<?php

namespace App\Controller;

use App\Form\SearhCartType;
use App\Repository\CartRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request,CartRepository $cart): Response
    {

        $searhCartForm=$this->createForm(SearhCartType::class);
        $cartData=[];
        if($searhCartForm->handleRequest($request)->isSubmitted() && $searhCartForm->isValid()){
            $criteria=$searhCartForm->getData();          
            $cartData=$cart->searchCart($criteria);
        }else{
            $cartData=$cart->findAll();
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'Home',
            'search_Form'=> $searhCartForm->createView(),
            'cartData'=>$cartData
        ]);
    }
}
