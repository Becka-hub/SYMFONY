<?php

namespace App\Controller;

use App\Shared\Reponses;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    private Reponses $reponses;

    public function __construct(Reponses $reponses)
    {
        $this->reponses = $reponses;
    }

    #[Route('/', name: 'default')]
    public function index(): Response
    {
        return $this->reponses->success('Bienvenue dans mon Apps');
    }
}
