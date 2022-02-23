<?php

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class SendMailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail($to,$subject,$message)
    {
        // sleep(3);
        $email = (new Email())
            ->from("Ansta@gmail.com") // natao any anaty service.yaml
            ->to($to)
            ->subject($subject)
            ->html('<p>' . $message . '</p>');
        $this->mailer->send($email);
    }
}
