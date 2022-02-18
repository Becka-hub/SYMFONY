<?php

namespace App\Controller;

use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class HomeController extends AbstractController
{

    private UserRepository $userRepository;


    public function __construct(UserRepository $userRepository)
    {
  
        $this->userRepository = $userRepository;
  
    }

    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/login', name: 'login', methods: 'POST')]
    public function login(JWTTokenManagerInterface $tokenManager,UserPasswordHasherInterface $hasher): Response
    {
        $data=json_decode(file_get_contents('php://input'));
        if (!isset($data->email, $data->password) || ($data->email === "" || $data->password === "")) {
           return $this->json(['error'=>'Form Invalid'],400);
        }
        $user = $this->userRepository->findOneBy(["email" => $data->email]);
        if (!$user) {
            return $this->json(['error'=>'User not found'],400);
        }

        if (!$hasher->isPasswordValid($user, $data->password)) {
            return $this->json(['error'=>'mot de passe incorrect'],400);
        }

         return $this->json([
               'data'=>$user,
               'token' => $tokenManager->create($user)
                        ],200);
    }

    // #[Route('/forgotPassword', name: 'forgotPassword',methods:'POST')]
    // public function forgotPassword(MailerInterface $mailer,TokenGeneratorInterface $tokenGenerator): Response
    // {
    //     $form=json_decode(file_get_contents('php://input'));
    //     if($form->email==""){
    //         return $this->json(['error'=>'Form Invalid'],400);
    //     }
    //     $user = $this->userRepository->findOneBy(["email" => $form->email]);
    //     if (!$user) {
    //         return $this->json(['error'=>'Email Incorrect'],400);
    //     }
    //     $user->setForgotPasswordToken($tokenGenerator->generateToken())
    //          ->setForgotPasswordTokenRequestedAt(new \DateTime('now'))
    //          ->setForgotPasswordTokenMustBeVerifiedBefore(new \DateTime('+15 minites'));

    //     $email = (new TemplatedEmail())
    //     ->from("Beckas@gmail.com")// natao any anaty service.yaml
    //     ->to(new Address($user->getEmail(),'Beckas'))
    //     ->subject('Resultat du Test :')
    //     ->htmlTemplate('home/email.html.twig')
    //     ->context([
    //        'devivery_date'=> date_create('+3 days'),
    //        'order_number'=>rand(5,5000),
    //     ]);
    //     $mailer->send($email);
    //     return $this->json(['success'=>'Email Envoyer avec success'],200);
    // }
}
