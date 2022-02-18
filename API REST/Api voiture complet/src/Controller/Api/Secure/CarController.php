<?php

namespace App\Controller\Api\Secure;

use App\Entity\Car;
use App\Shared\Messages;
use App\Shared\Reponses;
use App\Service\Service;
use App\Repository\CarRepository;
use Symfony\Component\HttpFoundation\Request;
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
    private Service $service;
    public function __construct(CarRepository $carRepository, Reponses $reponse,Service $service)
    {
        $this->carRepository = $carRepository;
        $this->reponse = $reponse;
        $this->service=$service;
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

    
    #[Route('/carSave', name: 'carSave', methods: 'POST')]
    public function carSave(Request $request): Response
    {
        $name = $request->request->get('name');
        //  return $this->json($name);
        $mark = $request->request->get('mark');
            // return $this->json($mark);
        $number = $request->request->get('number');
        // return $this->json($number);
        $image = $request->files->get('image');
        if (!isset($name, $mark, $number,$image) || ($name === "" || $mark === "" || $number === ""|| $image === "")) {
            return $this->reponse->error(Messages::FORM_INVALID);
        }
        $extension = $image->getClientOriginalExtension();
        $image_name = time() . '.' . $extension;
        $image->move($this->getParameter('brochures_directory'), $image_name);

        $car = (new Car())
            ->setName($name)
            ->setMark($mark)
            ->setNumber($number)
            ->setImage($image_name);
        $this->service->em()->persist($car);
        $this->service->em()->flush();
        return $this->reponse->success($car->tojson());
    }

    #[Route('/carDelete/{id}', name: 'carDelete', methods: 'DELETE')]
    public function delete(int $id): Response
    {
        $car = $this->carRepository->findOneBy(['id' => $id]);
        if (!$car) return $this->reponse->error(Messages::CAR_NOT_FOUND);
        $image=$car->getImage();
        $nomImage = $this->getParameter('brochures_directory') . '/' . $image;
        if (file_exists($nomImage)) {
            unlink($nomImage);
        }
        $this->service->em()->remove($car);
        $this->service->em()->flush();
        return $this->reponse->success("Deleted");
    }

    #[Route('/carUpdate/{id}', name: 'carUpdate', methods: 'POST')]
    public function carUpdate(Request $request,$id): Response
    {
        $name = $request->request->get('name');
         // return $this->json($name);
        $mark = $request->request->get('mark');
            // return $this->json($mark);
        $number = $request->request->get('number');
        // return $this->json($number);
        $image = $request->files->get('image');
        // return $this->json($image);
        if (!isset($name, $mark, $number) || ($name === "" || $mark === "" || $number === "")) {
            return $this->reponse->error(Messages::FORM_INVALID);
        }

        $car=$this->carRepository->find($id);
        $car->setName($name);
        $car->setMark($mark);
        $car->setNumber($number);
        if($image || $image != null){
        $image_encien=$car->getImage();
        $nomImage = $this->getParameter('brochures_directory') . '/' . $image_encien;
        if (file_exists($nomImage)) {
            unlink($nomImage);
        }

        $extension = $image->getClientOriginalExtension();
        $image_name = time() . '.' . $extension;
        $image->move($this->getParameter('brochures_directory'), $image_name);
        $car->setImage($image_name);
        }


        $this->service->em()->persist($car);
        $this->service->em()->flush();
        return $this->reponse->success($car->tojson());
    }

}
