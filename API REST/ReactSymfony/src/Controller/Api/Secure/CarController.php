<?php

namespace App\Controller\Api\Secure;

use App\Entity\Car;
use App\Shared\Messages;
use App\Shared\Reponses;
use App\Repository\CarRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/car', name: 'full_car_ctrl')]
#[Security("is_granted('ROLE_USER')")]
class CarController extends AbstractController
{
    private CarRepository $carRepository;
    private Reponses $reponse;

    public function __construct(CarRepository $carRepository, Reponses $reponse)
    {
        $this->carRepository = $carRepository;
        $this->reponse = $reponse;
    }

    #[Route('/carList', name: 'carList', methods: 'GET')]
    public function car(): Response
    {
        $cars = $this->carRepository->findAll();
        return $this->reponse->success(array_map(function (Car $car) {
            return $car->tojson();
        }, $cars));
    }


    #[Route('/carSingle/{id}', name: 'carSingle', methods: 'GET')]
    public function carSingle(int $id): Response
    {
        $car = $this->carRepository->findOneBy(['id' => $id]);

        if ($car == "") {
            return $this->reponse->error(Messages::CAR_NOT_FOUND);
        } else {
          return  $this->reponse->success(
                $car->tojson()
            );
        }
    }
}
