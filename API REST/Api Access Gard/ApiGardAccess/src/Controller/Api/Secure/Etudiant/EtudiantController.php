<?php

namespace App\Controller\Api\Secure\Etudiant;

use App\Entity\Message;
use App\Service\Service;
use App\Shared\Messages;
use App\Shared\Reponses;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user/api', name: 'user_ctrl')]
#[Security("is_granted('ROLE_USER')")]
class EtudiantController extends AbstractController
{
    private Reponses $reponse;
    private UserRepository $user;
    private MessageRepository $messageRepository;
    public function __construct(Reponses $reponse, Service $service, UserRepository $user,MessageRepository $messageRepository)
    {
        $this->reponse = $reponse;
        $this->service = $service;
        $this->user = $user;
        $this->messageRepository=$messageRepository;
    }

    #[Route('/afficheEtudiant/{id}', name: 'afficheEtudiant', methods: 'GET')]
    public function afficheEtudiant($id): Response
    {
        $user = $this->user->findOneBy(["id"=>$id]);
        if(!$user){
            return $this->reponse->error(Messages::USER_NOT_FOUND);
        }
        return $this->reponse->success(
            $user->tojson()
        );
    }

    #[Route('/afficheOneMessage/{id}', name: 'afficheOneMessage', methods: 'GET')]
    public function afficheOneMessage($id)
    {
        $messages=$this->messageRepository->findBy(["receiver"=>$id]);
        return $this->reponse->success(array_map(function (Message $message) {
            return $message->tojson();
        }, $messages));
    }

    #[Route('/afficheMessage', name: 'afficheMessage', methods: 'GET')]
    public function afficheMessage()
    {
        $messages=$this->messageRepository->findAll();
        return $this->reponse->success(array_map(function (Message $message) {
            return $message->tojson();
        }, $messages));
    }

}
