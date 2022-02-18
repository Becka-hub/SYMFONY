<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher=$userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        
        for($i=0;$i<20;$i++){
            $produit = new Produit();
            $category = new Category();
            $produit->setName('name'.$i);
            $produit->setQuantite($i);
            $category->setName('category'.$i);
            $produit->setCategory($category);
            $manager->persist($produit);
        }
        
            $user = new User();
            $user->setEmail("beckas@gmail.com");
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    "1234"
                )
                );
                $manager->persist($user);
       
        
        $manager->flush();
    }
}
