<?php

namespace App\Controller;

use App\Entity\Image;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/ajouteImage', name: 'ajouteImage', methods: 'POST')]
    public function ajouteImage(ManagerRegistry $managerRegistry): Response
    {
        $datas = json_decode(file_get_contents('php://input'));
        if (!isset($datas->name, $datas->image) || ($datas->name === "" || $datas->image === "")) {
            return $this->json(['Title' => 'formInvalide'], 400);
        }

        $image_name = time() . '.png';
        $fileName = $this->getParameter('brochures_directory') . $image_name;
        $file = fopen($fileName, 'wb');
        $data = explode(',', $datas->image);
        fwrite($file, base64_decode(count($data) === 2 ? $data[1] : $data[0]));
        fclose($file);
        $img= new Image();
        $img->setName($datas->name);
        $img->setImage($image_name);
        $managerRegistry->getManager()->persist($img);
        $managerRegistry->getManager()->flush();

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
