<?php

namespace App\Shared;

use App\Shared\Messages;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Reponses extends AbstractController
{
    public function success($data = null, array $message = Messages::SUCCESS): Response
    {
        return $this->json([
            'title' => $message['title'],
            'message' => $message['message'],
            'data' => $data,
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
