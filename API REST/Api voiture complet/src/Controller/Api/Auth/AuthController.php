<?php

namespace App\Controller\Api\Auth;

use App\Entity\User;
use App\Service\Service;
use App\Shared\Messages;
use App\Shared\Reponses;
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
    private UserRepository $userRepository;
    public function __construct(Reponses $reponse, UserRepository $userRepository, Service $service)
    {
        $this->reponse = $reponse;
        $this->service = $service;
        $this->userRepository = $userRepository;
    }

    #[Route('/login', name: 'login', methods: 'POST')]
    public function login(JWTTokenManagerInterface $tokenManager): Response
    {
        $data = $this->service->json_decode();
        if (!isset($data->email, $data->password) || ($data->email === "" || $data->password === "")) {
            return $this->reponse->error(Messages::FORM_INVALID);
        }
        $user = $this->userRepository->findOneBy(["email" => $data->email]);
        if (!$user) {
            return $this->reponse->error(Messages::USER_NOT_FOUND);
        }

        if (!$this->service->hasher()->isPasswordValid($user, $data->password)) {
            return $this->reponse->error(Messages::PASSWORD_WRONG);
        }

        return $this->reponse->success([
            'data' => $user->tojson(),
            'token' => $tokenManager->create($user)
        ]);
    }

    #[Route('/register', name: 'register', methods: 'POST')]
    public function register(): Response
    {
        $data = $this->service->json_decode();
        if (!isset($data->email,$data->nom, $data->password) || ($data->email === "" || $data->nom==="" || $data->password === "")) {
            return $this->reponse->error(Messages::FORM_INVALID);
        }
        $users = $this->userRepository->findOneBy(["email" => $data->email]);
        if ($users) {
            return $this->reponse->error(Messages::EMAIL_USED);
        }
        if (strlen($data->password) < 6) {
            return $this->reponse->error(Messages::PASSWORD_TOO_SHORT);
        }
        $user = new User(); 
           $user->setEmail($data->email);
           $user->setPassword($this->service->hasher()->hashPassword(
            $user,
            $data->password));
           $user->setNom($data->nom);
        $this->service->em()->persist($user);
        $this->service->em()->flush();
        return $this->reponse->success([
            "user"=>$user->tojson()
        ]);
    }
}
