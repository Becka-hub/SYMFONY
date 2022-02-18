<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Address;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Home',
        ]);
    }
    
    #[Route('/mail', name: 'mail')]
    public function mail(MailerInterface $mailer,$appEmail,$publicDir): Response
    {
        // $email = (new Email())
        //   ->from($appEmail)// natao any anaty service.yaml
        //   ->to('MAMINIAINAZAIN@gmail.com')
        //   ->subject('Resultat du Test :')
        //   ->html('<p>Merci de votre test mais vous êtes pas prise</p>');
        // $mailer->send($email);

        $email = (new TemplatedEmail())
        ->from($appEmail)// natao any anaty service.yaml
        ->to(new Address('MAMINIAINAZAIN@gmail.com','Beckas'))
        ->subject('Resultat du Test :')
        ->htmlTemplate('emails/order-confirmation.html.twig')
        ->attachFromPath($publicDir.'/pdf/example.pdf')
        ->context([
           'devivery_date'=> date_create('+3 days'),
           'order_number'=>rand(5,5000),
        ]);
        $mailer->send($email);
        return new Response('Email sent');
    }

}
