<?php
namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class Service extends AbstractController
{
    private SluggerInterface $slugger;
    public function __construct( SluggerInterface $slugger )
    {
        $this->slugger=$slugger;
    }
   
    public function uploadFile($data, $directory)
    {
        $originalImageName = pathinfo($data->getClientOriginalName(), PATHINFO_FILENAME);
        $safeImageName = $this->slugger->slug($originalImageName);
        $imageName = $safeImageName . '-' . uniqid() . '.' . $data->guessExtension();

        try {
            $data->move(
                $directory,
                $imageName
            );
            return $imageName;
        } catch (FileException $e) {
        }
    }

}