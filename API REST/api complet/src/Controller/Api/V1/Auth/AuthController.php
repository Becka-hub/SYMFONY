<?php

namespace App\Controller\Api\V1\Auth;

use App\Shared\Reponses;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/auth', name: 'auth_ctrl')]
class AuthController extends AbstractController
{
    private Reponses $reponses;

    public function __construct(Reponses $reponses)
    {
        $this->reponses = $reponses;
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->reponses->success("Connected");
    }

    #[Route('/register', name: 'register')]
    public function register(): Response
    {
        return $this->reponses->success("Inserted");
    }
}
