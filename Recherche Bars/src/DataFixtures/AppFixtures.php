<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=0;$i < 20;$i++){
           $article = new Article();
           $article->setTitle('vary'.$i);
           $article->setContent('Sakafo'.$i);
           $manager->persist($article);
        }
        
        $manager->flush();
    }
}
