<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchController extends AbstractController
{
    #[Route('/', name: 'search')]
    public function index(ArticleRepository $repo): Response
    {
        $form = $this->createFormBuilder()
        ->setAction($this->generateUrl('handleSearch'))
        ->add('query', TextType::class, [
            'label' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Entrez un mot-clé'
            ]
        ])
        ->add('recherche', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary'
            ]
        ])
        ->getForm();

        $data= $repo->findAll();

        return $this->render('search/index.html.twig', [
            'controller_name' => 'Search',
            'form' => $form->createView(),
            'data'=> $data
        ]);
    }
 
    #[Route('/handleSearch', name: 'handleSearch')]
    public function handleSearch(Request $request, ArticleRepository $repo)
    {
        $query = $request->request->get('form')['query'];
        if($query!=="") {
            $articles = $repo->findArticlesByName($query);
        }
        return $this->render('search/home.html.twig', [
            'controller_name' => 'Search',
            'articles' => $articles
        ]);
    }
}
