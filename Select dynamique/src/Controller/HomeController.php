<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Country;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
        $form = $this->createFormBuilder()
            ->add('name', TextType::class,[
                'constraints' => new NotBlank(['message'=> 'S\'il vous plait entrer votre nom !'])
            ])
            ->add('country', EntityType::class, [
                 
                'placeholder' => 'Please choose a country',
                'class' => Country::class,
                'choice_label' => 'name',
                'query_builder' => function () {
                    return $this->country->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                },
                'constraints' => new NotBlank(['message'=> 'S\'il vous plait sélectionner un pays !'])
            ])
            ->add(
                'city',
                EntityType::class,
                [   
                    'placeholder' => 'Please choose a city',
                    'class' => City::class,
                    'choice_label' => 'name',
                    'disabled' => true,
                    'query_builder' => function () {
                        return $this->city->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                    },
                    'constraints' => new NotBlank(['message'=> 'S\'il vous plait sélectionner un ville !'])
                ]
            )
            ->add('message', TextareaType::class,[
                'constraints' => [
                    new NotBlank(['message'=> 'S\'il vous plait entrer un message !']),
                    new Length(['min'=>5,'minMessage'=>'S\'il vous plait le message ne doit pas être inférieur a {{limit}} caractères !'])
                ]
            ])
            ->add('availableAt', DateTimeType::class)
            ->getForm();

            // $form->setData(['name'=>'symfony','message'=>'hello','availableAt'=>new \DateTime('+2 days')]);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                 dd($form->getData());
            }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'Home',
            'form' => $form->createView()
        ]);
    }
}
