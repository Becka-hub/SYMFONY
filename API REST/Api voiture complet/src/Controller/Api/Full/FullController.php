<?php

namespace App\Controller\Api\Full;

use App\Entity\Car;
use App\Shared\Reponses;
use App\Repository\CarRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/full', name: 'full_car_ctrl')]
class FullController extends AbstractController
{
    private CarRepository $carRepository;
    private Reponses $reponse;

    public function __construct(CarRepository $carRepository, Reponses $reponse)
    {
        $this->carRepository = $carRepository;
        $this->reponse = $reponse;
    }

    #[Route('/car', name: 'car', methods: 'GET')]
    public function car(): Response
    {
        $cars = $this->carRepository->findAll();
        return $this->reponse->success(array_map(function (Car $car) {
            return $car->tojson();
        }, $cars));
    }
}
