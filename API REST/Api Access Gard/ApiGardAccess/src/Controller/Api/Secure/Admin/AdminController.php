<?php

namespace App\Controller\Api\Secure\Admin;

use App\Entity\Mail;
use App\Entity\User;
use App\Entity\Vage;
use App\Entity\Message;
use App\Service\Service;
use App\Shared\Messages;
use App\Shared\Reponses;
use App\Entity\Etudiants;
use App\Message\SendMailMessage;
use App\Service\SendMailService;
use App\Repository\UserRepository;
use App\Repository\VageRepository;
use App\Repository\MessageRepository;
use App\Repository\EtudiantsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;

#[Route('/admin/api', name: 'admin_ctrl')]
#[Security("is_granted('ROLE_ADMIN')")]
class AdminController extends AbstractController
{
    private Reponses $reponse;
    private Service $service;
    private EtudiantsRepository $etudiantRepository;
    private VageRepository $vageRepository;
    private SendMailService $sendMail;
    private MessageRepository $messageRepository;
    public function __construct(Reponses $reponse, Service $service, EtudiantsRepository $etudiantRepository, UserRepository $userRepository, VageRepository $vageRepository, SendMailService $sendMail, MessageRepository $messageRepository)
    {
        $this->reponse = $reponse;
        $this->service = $service;
        $this->etudiantRepository = $etudiantRepository;
        $this->userRepository = $userRepository;
        $this->vageRepository = $vageRepository;
        $this->sendMail = $sendMail;
        $this->messageRepository = $messageRepository;
    }

    #[Route('/afficheInscrie', name: 'afficheInscrie', methods: 'GET')]

    public function afficheInscrie(): Response
    {
        $etudiants = $this->etudiantRepository->findAll();
        return $this->reponse->success(array_map(function (Etudiants $etudiant) {
            return $etudiant->tojson();
        }, $etudiants));
    }

    #[Route('/ajouteMatricule/{id}', name: 'ajouteMatricule', methods: 'POST')]
    public function ajouteMatricule($id): Response
    {
        $etudiant = $this->etudiantRepository->find($id);
        if (!$etudiant) {
            return $this->reponse->error(Messages::USER_NOT_FOUND);
        }
        $data = $this->service->json_decode();
        if (!isset($data->password, $data->vage) || ($data->password === "" || $data->vage === "")) {
            return $this->reponse->error(Messages::FORM_INVALID);
        }
        if (strlen($data->password) < 6) {
            return $this->reponse->error(Messages::PASSWORD_TOO_SHORT);
        }

        $user = new User();
        $user->setEtudiant($etudiant);
        $user->setEmail($etudiant->getAdresseMail());
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->service->hasher()->hashPassword($user, $data->password));

        $vage = new Vage();
        $vage->setEtudiant($etudiant);
        $vage->setSemestre($data->vage);

        $this->service->em()->persist($user);
        $this->service->em()->persist($vage);
        $this->service->em()->flush();
        $this->sendMail->sendMail($etudiant->getAdresseMail(), "Mot de passe etudiant", $data->password);
        return $this->reponse->success([
            "user" => $user->tojson(),
            "vague" => $vage->tojson()
        ]);
    }

    #[Route('/afficheVage', name: 'afficheVage', methods: 'GET')]

    public function afficheVage(): Response
    {
        $vages = $this->vageRepository->findAll();
        return $this->reponse->success(array_map(function (Vage $vage) {
            return $vage->tojson();
        }, $vages));
    }

    #[Route('/afficheOneVage/{vage}', name: 'afficheOneVage', methods: 'GET')]

    public function afficheOneVage($vage): Response
    {

        $vages = $this->vageRepository->findBy(['semestre' => $vage]);

        if (!$vages) {
            return $this->reponse->error(Messages::VAGUE_NOT_FOUND);
        }

        return $this->reponse->success(array_map(function (Vage $vage) {
            return $vage->tojson();
        }, $vages));
    }

    #[Route('/sendMailEtudiant/{id}', name: 'sendMailEtudiant', methods: 'POST')]

    public function sendMailEtudiant($id): Response
    {
        $etudiant = $this->etudiantRepository->findOneBy(['id' => $id]);
        if (!$etudiant) {
            return $this->reponse->error(Messages::USER_NOT_FOUND);
        }

        $data = $this->service->json_decode();
        if (!isset($data->message, $data->subject) || ($data->message === "" || $data->subject === "")) {
            return $this->reponse->error(Messages::FORM_INVALID);
        }

        $mail = new Mail();
        $mail->setSubject($data->subject);
        $mail->setMessage($data->message);
        $mail->setEtudiant($etudiant);
        $this->service->em()->persist($mail);
        $this->service->em()->flush($mail);

        $this->sendMail->sendMail($etudiant->getAdresseMail(), $data->subject, $data->message);
        return $this->reponse->success('Email envoyé avec success !');
    }


    #[Route('/sendMailVage/{vage}', name: 'sendMailVage', methods: 'POST')]

    public function sendMailVage($vage,MessageBusInterface $messageBus): Response
    {
        $vage = $this->vageRepository->findBy(['semestre' => $vage]);

        if (!$vage) {
            return $this->reponse->error(Messages::VAGUE_NOT_FOUND);
        }

        $data = $this->service->json_decode();

        if (!isset($data->message, $data->subject) || ($data->message === "" || $data->subject === "")) {
            return $this->reponse->error(Messages::FORM_INVALID);
        }

        foreach ($vage as $etudiant) {
            $mail = new Mail();
            $mail->setSubject($data->subject);
            $mail->setMessage($data->message);
            $mail->setEtudiant($etudiant->getEtudiant());
            $this->service->em()->persist($mail);
            $this->service->em()->flush($mail);
            $messageBus->dispatch(new SendMailMessage($etudiant->getEtudiant()->getId(),$mail->getId()));
            // $this->sendMail->sendMail($etudiant->getEtudiant()->getAdresseMail(), $data->subject, $data->message);


        }

        return $this->reponse->success('Email envoyé avec success !');
    }



    #[Route('/sendMessage/{id}', name: 'sendMessage', methods: 'POST')]

    public function sendMessage($id): Response
    {
        $receiver = $this->etudiantRepository->findOneBy(["id" => $id]);
        $data = $this->service->json_decode();
        if (!isset($data->message, $data->idSender) || ($data->message === "" || $data->idSender === "")) {
            return $this->reponse->error(Messages::FORM_INVALID);
        }
        $sender = $this->etudiantRepository->findOneBy(["id" => $data->idSender]);
        $message = new Message();
        $message->setAuthor($sender->getAdresseMail());
        $message->setMessage($data->message);
        $message->setSender($sender);
        $message->setReceiver($receiver);
        $message->setIsView(false);
        $this->service->em()->persist($message);
        $this->service->em()->flush();
        return $this->reponse->success("Message envoyé avec success !");
    }

}
