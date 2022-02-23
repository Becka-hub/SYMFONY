<?php

namespace App\Message;

final class SendMailMessage
{
    private $userId;
    private $emailId;
    
    public function __construct(int $userId,int $emailId)
    {
        $this->userId=$userId;
        $this->emailId=$emailId;
    }
   
    public function getUserId()
    {
        return $this->userId;
    }

    public function getEmailId()
    {
        return $this->emailId;
    }
}
