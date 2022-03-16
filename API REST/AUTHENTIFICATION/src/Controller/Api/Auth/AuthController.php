<?php

namespace App\Controller\Api\Auth;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthController extends AbstractController
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $hasher;
    private ManagerRegistry $managerRegistry;
    public function __construct(UserRepository $userRepository,UserPasswordHasherInterface $hasher,ManagerRegistry $managerRegistry)
    {
        $this->userRepository=$userRepository;
        $this->hasher=$hasher;
        $this->managerRegistry=$managerRegistry;
    }
    #[Route('/inscription', name: 'inscription', methods: 'POST')]
    public function inscription(): Response
    {
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($data->email, $data->password) || ($data->email === "" || $data->password === "")) {
            return $this->json(['Warning' => 'Form Invalid'], 500);
        }
        $user=$this->userRepository->findOneBy(["email"=>$data->email]);
        if($user){
            return $this->json(['warning'=>'Email Used'],500);
        }
        $user=new User();
        $user->setEmail($data->email);
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->hasher->hashPassword($user, $data->password));
        $this->managerRegistry->getManager()->persist($user);
        $this->managerRegistry->getManager()->flush();
        return $this->json(['status'=>true,'title'=>"success",'message'=>"Insertion avec success","donner"=>$user],200);

    }

    #[Route('/login', name: 'login', methods: 'POST')]
    public function login(JWTTokenManagerInterface $tokenManager): Response
    {
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($data->email, $data->password) || ($data->email === "" || $data->password === "")) {
            return $this->json(['Warning' => 'Form Invalid'], 500);
        }
        $user=$this->userRepository->findOneBy(["email"=>$data->email]);
        if(!$user){
            return $this->json(['warning'=>'User Not Exist'],500);
        }
        if (!$this->hasher->isPasswordValid($user, $data->password)) {
            return $this->json(['message'=>'mot de passe incorrect'],400);
        }

        return $this->json(['title'=>'success','donner'=>$user,'token' => $tokenManager->create($user)],200);
    }

}
