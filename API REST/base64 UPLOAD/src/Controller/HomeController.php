<?php

namespace App\Controller;

use App\Entity\Image;
use App\Service\Service;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    private Service $service;

    public function __construct(Service $service)
    {
        $this->service=$service;
    }

    #[Route('/ajouteImage', name: 'ajouteImage', methods: 'POST')]
    public function ajouteImage(ManagerRegistry $managerRegistry): Response
    {
        $datas = $this->service->json_decode();

        if (!isset($datas->name, $datas->image) || ($datas->name === "" || $datas->image === "")) {
            return $this->json(['Title' => 'formInvalide'], 400);
        }

        $type='.png';
        $brochure = $this->getParameter('brochures_directory');
        $image_name =$this->service->fichier64($type,$brochure,$datas->image);
        $img= new Image();
        $img->setName($datas->name);
        $img->setImage($image_name);
        $this->service->em()->persist($img); 
        $this->service->em()->flush();
        return $this->json(['message' => 'Insertion avec success'], 200);
    }












    // public function base64ToFile($base64_string, $output_File)
    // {
    //     $file = fopen($output_File, "wb");
    //     $data = explode(',', $base64_string);
    //     fwrite($file, base64_decode($data[1]));
    //     fclose($file);
    //     return $output_File;
    // }
}
