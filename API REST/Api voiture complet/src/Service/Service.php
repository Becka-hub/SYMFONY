<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Service
{
    private UserPasswordHasherInterface $hasher;
    private ManagerRegistry $managerRegistry;

    public function __construct(UserPasswordHasherInterface $hasher, ManagerRegistry $managerRegistry)
    {
        $this->hasher = $hasher;
        $this->managerRegistry = $managerRegistry;
    }
    
    public function json_decode()
    {
        try {
            return file_get_contents('php://input') ?
                json_decode(file_get_contents('php://input')) : [];
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function em()
    {
        return $this->managerRegistry->getManager();
    }

    public function hasher()
    {
        return $this->hasher;
    }
}
