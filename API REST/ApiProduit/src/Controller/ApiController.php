<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/ajouteProduit', name: 'ajouteProduit', methods: 'POST')]
    public function ajouteProduit(Request $request,ManagerRegistry $managerRegistry): Response
    {
        $libelle = $request->request->get('libelle');
        //  return $this->json($name);
        $price = $request->request->get('price');
            // return $this->json($mark);
        $content = $request->request->get('content');
        // return $this->json($number);
        $image = $request->files->get('image');
        if (!isset($libelle, $price, $content,$image) || ($libelle === "" || $price === "" || $content === ""|| $image === "")) {
            return $this->json(['status'=>false,'title'=>"Error",'message'=>"Form invalid"],400);
        }
        $extension = $image->getClientOriginalExtension();
        $image_name = time() . '.' . $extension;
        $image->move($this->getParameter('brochures_directory'), $image_name);

        $produit= new Produit();
        $produit->setLibelle($libelle);
        $produit->setPrice($price);
        $produit->setContent($content);
        $produit->setImage($image_name);
        $managerRegistry->getManager()->persist($produit);
        $managerRegistry->getManager()->flush();
        return $this->json(['status'=>true,'title'=>"Success",'Message'=>"Insertion avec success","Donner"=>$produit],200);
    }

    #[Route('/afficheProduit', name: 'afficheProduit', methods: 'GET')]
    public function afficheProduit(ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->findAll();
        return $this->json(['status'=>true,'title'=>"Success","TotalRows"=>count($produit),'Donner'=>$produit],200);
    }

    #[Route('/afficheProduitDetails/{id}', name: 'afficheProduitDetails', methods: 'GET')]
    public function afficheProduitDetails($id,ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->find($id);
        if(!$produit){
            return $this->json(['status'=>false,'title'=>"Error"],400);
        }
        return $this->json(['status'=>true,'title'=>"Success",'Donner'=>$produit],200);
    }

    #[Route('/deleteProduit/{id}', name: 'deleteProduit', methods: 'DELETE')]
    public function deleteProduit($id,ProduitRepository $produitRepository,ManagerRegistry $managerRegistry): Response
    {
      $produit = $produitRepository->find($id);
        $managerRegistry->getManager()->remove($produit);
        $managerRegistry->getManager()->flush();
        return $this->json(['status'=>true,'title'=>"Success",'Message'=>"Suppression avec success"],200);

    }
}
