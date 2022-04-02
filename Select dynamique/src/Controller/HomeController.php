<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Country;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Form\TicketFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private CountryRepository $country;
    private CityRepository $city;

    public function __construct(CountryRepository $country, CityRepository $city)
    {
        $this->country = $country;
        $this->city = $city;
    }


    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {

        $form=$this->createForm(TicketFormType::class,);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                // dd($form->get('availableAt')->get('date'));
                // dd($form->get('availableAt')->get('time'));
                $data=$form->getData();
                dd($data['city']);
            }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'Home',
            'form' => $form->createView()
        ]);
    }
}
