<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Shared\Reponses;
class HomeController extends AbstractController
{
    private Reponses $reponses;

    public function __construct(Reponses $reponses)
    {
        $this->reponses = $reponses;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->reponses->success('Bienvenue dans mon Apps');
    }
}
