<?php

namespace App\Controller;

use App\Shared\Reponses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private Reponses $reponse;
    public function __construct(Reponses $reponse)
    {
        $this->reponse=$reponse;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
      return $this->reponse->success("Connexion success");
    }
}
