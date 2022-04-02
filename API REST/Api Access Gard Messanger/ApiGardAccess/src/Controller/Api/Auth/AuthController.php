<?php

namespace App\Controller\Api\Auth;

use App\Service\Service;
use App\Shared\Messages;
use App\Shared\Reponses;
use App\Entity\Etudiants;
use App\Repository\EtudiantsRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

#[Route('/auth', name: 'auth_ctrl')]
class AuthController extends AbstractController
{
    private Reponses $reponse;
    private Service $service;
    private UserRepository $user;
    private EtudiantsRepository $etudiant;
    public function __construct(Reponses $reponse, Service $service, UserRepository $user,EtudiantsRepository $etudiant)
    {
        $this->reponse = $reponse;
        $this->service = $service;
        $this->user = $user;
        $this->etudiant=$etudiant;
    }

    #[Route('/inscription', name: 'inscription', methods: 'POST')]
    public function inscription(): Response
    {
        $data = $this->service->json_decode();
        if (!isset($data->nom, $data->prenom,$data->email) || ($data->nom === "" || $data->prenom === "" || $data->email === "")) {
            return $this->reponse->error(Messages::FORM_INVALID);
        }
        $user=$this->etudiant->findOneBy(["adresseMail"=>$data->email]);
        if($user){
            return $this->reponse->error(Messages::EMAIL_USED);
        }
        $etudiant = new Etudiants();
        $etudiant->setNom($data->nom);
        $etudiant->setPrenom($data->prenom);
        $etudiant->setAdresseMail($data->email);
        $this->service->em()->persist($etudiant);
        $this->service->em()->flush();
        return $this->reponse->success([
            "message"=>"Inscription avec success receiver par email votre mot de passe apres validation de 24h",
            "etudiant" => $etudiant->tojson()
        ]);
    }

    #[Route('/login', name: 'login', methods: 'POST')]
    public function login(JWTTokenManagerInterface $tokenManager): Response
    {
        $data = $this->service->json_decode();
        if (!isset($data->email, $data->mdp) || ($data->email === "" || $data->mdp === "")) {
            return $this->reponse->error(Messages::FORM_INVALID);
        }
        $user = $this->user->findOneBy(['email' => $data->email]);

        if (!$user) {
            return $this->reponse->error(Messages::USER_NOT_FOUND);
        }

        if(!$this->service->hasher()->isPasswordValid($user, $data->mdp)){
            return $this->reponse->error(Messages::PASSWORD_WRONG);
        }
        return $this->reponse->success([
            'etudiant' => $user->tojson(),
            'token' => $tokenManager->create($user)
        ]);
    }
}
