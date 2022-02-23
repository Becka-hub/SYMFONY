<?php

namespace App\Shared;

use App\Shared\Messages;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Reponses extends AbstractController
{
    public function success($donner = null, array $message = Messages::SUCCESS): Response
    {
        return $this->json([
            'title' => $message['title'],
            'message' => $message['message'],
            'donner' => $donner,
        ], $message['code']);
    }
    public function error(array $message = Messages::ERROR): Response
    {
        return $this->json([
            'title' => $message['title'],
            'message' => $message['message'],
        ], $message['code']);
    }
}
