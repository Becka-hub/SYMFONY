<?php

namespace App\MessageHandler;

use App\Entity\Mail;
use App\Entity\Etudiants;
use App\Message\SendMailMessage;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SendMailMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $em;
    private SendMailService $emailService;

    public function __construct(EntityManagerInterface $em,SendMailService $emailService)
    {
        $this->em=$em;
        $this->emailService=$emailService;
    }

    public function __invoke(SendMailMessage $message)
    {
        $user=$this->em->find(Etudiants::class,$message->getUserId());
        $email=$this->em->find(Mail::class,$message->getEmailId());
        if($email!== null && $user !==null){
            $this->emailService->sendMail($user->getAdresseMail(),$email->getSubject(),$email->getMessage());
        }
    }

}
