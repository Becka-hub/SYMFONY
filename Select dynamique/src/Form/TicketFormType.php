<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\City;
use App\Entity\Country;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
class TicketFormType extends AbstractType
{

    private CountryRepository $country;
    private CityRepository $city;

    public function __construct(CountryRepository $country, CityRepository $city)
    {
        $this->country = $country;
        $this->city = $city;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

    // NET

    //     $builder

    //     ->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event) {
    //         $country=$event->getData()['country'] ?? null;

    //         $cities= $country=== null ? [] : $this->city->findByCountry($country,['name'=>'ASC']);

    //       //   $cities= $country=== null ? [] : $this->city->createQueryBuilder('c')
    //       //   ->andWhere('c.country=:country')
    //       //   ->setParameter('country',$country)
    //       //   ->orderBy('c.name','ASC')
    //       //   ->getQuery()
    //       //   ->getResult();

    //         $event->getForm()->add('city',EntityType::class,[
    //             'class'=>City::class,
    //             'choice_label' => 'name',
    //             'choices'=>$cities,
    //             'disabled'=>$country === null,
    //             'placeholder'=>'Please select city',
    //             'constraints' => new NotBlank(['message'=> 'S\'il vous plait sélectionner un ville !'])
    //         ]);

    //       })





    // $builder

    //       ->add('name', TextType::class,[
    //           'attr'=>['placeholder'=>'Ajouter name...'],
    //           'constraints' => new NotBlank(['message'=> 'S\'il vous plait entrer votre nom !'])
    //       ])
    //       ->add('age', IntegerType::class,[
    //           'attr'=>['placeholder'=>'Ajouter name...'],
    //           'constraints' => new NotBlank(['message'=> 'S\'il vous plait entrer votre age !'])
    //       ])
    //       ->add('country', EntityType::class, [
    //           'placeholder' => 'Select country',
    //           'class' => Country::class,
    //           'choice_label' => 'name',
    //           'choice_value' => 'id',
    //           'query_builder' => function () {
    //               return $this->country->createQueryBuilder('c')->orderBy('c.name', 'ASC');
    //           },
    //           'constraints' => new NotBlank(['message'=> 'S\'il vous plait sélectionner un pays !'])
    //       ])
    //       ->add('message', TextareaType::class,[
    //           'attr'=>['placeholder'=>'Ajouter message...','rows'=>5],
    //           'constraints' => [
    //               new NotBlank(['message'=> 'S\'il vous plait entrer un message !']),
    //               new Length(['min'=>5,'minMessage'=>'S\'il vous plait le message ne doit pas être inférieur a {{limit}} caractères !'])
    //           ]
    //       ])
    //       ->add('availableAt', DateTimeType::class,[
    //           'widget'=>'single_text'
    //       ])


          // ->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event){
          //     // dd($event->getData()['age']); // => availableAt , age valeur mise en avant
          //     // dd($event->setData(['age'=>25]));
          //     $age=$event->getData()['age']?? null;
          //     if($age !== null && $age < 18 ){
          //       $event->getForm()->add('motherName',TextType::class);
          //     }else {
          //         $event->getForm()->remove('motherName');
          //     }

          // }) // ecouter si on avez mis un donner predefinie dans un champ comme availableAt et age




          // ->addEventListener(FormEvents::POST_SET_DATA,function(FormEvent $event){
          //     // dd($event->getForm()->getData());
          //     // dd($event->getData());
    
          // }) // recuperer les donner du form avant le submiter donc apres avoir remplir un case 
        // ;
   


        $builder
          ->add('name', TextType::class,[
              'attr'=>['placeholder'=>'Ajouter name...'],
              'constraints' => new NotBlank(['message'=> 'S\'il vous plait entrer votre nom !'])
          ])
          ->add('age', IntegerType::class,[
              'attr'=>['placeholder'=>'Ajouter name...'],
              'constraints' => new NotBlank(['message'=> 'S\'il vous plait entrer votre age !'])
          ])
          ->add('country', EntityType::class, [
              'placeholder' => 'Select country',
              'class' => Country::class,
              'choice_label' => 'name',
              'choice_value' => 'id',
              'query_builder' => function () {
                  return $this->country->createQueryBuilder('c')->orderBy('c.name', 'ASC');
              },
              'constraints' => new NotBlank(['message'=> 'S\'il vous plait sélectionner un pays !'])
          ])
          ->add('city', ChoiceType::class, [
            'placeholder' => 'Select city',
            'constraints' => new NotBlank(['message'=> 'S\'il vous plait sélectionner un ville !'])
           ])
          ->add('message', TextareaType::class,[
              'attr'=>['placeholder'=>'Ajouter message...','rows'=>5],
              'constraints' => [
                  new NotBlank(['message'=> 'S\'il vous plait entrer un message !']),
                  new Length(['min'=>5,'minMessage'=>'S\'il vous plait le message ne doit pas être inférieur a {{limit}} caractères !'])
              ]
          ])
          ->add('availableAt', DateTimeType::class,[
              'widget'=>'single_text'
          ]) ;
    
          $formModifier=function(FormInterface $form, Country $country=null){

             $city=(null===$country)? [] : $country->getCities();
             $form->add('city',EntityType::class,[
               'class'=>City::class,
               'choices'=>$city,
               'choice_label'=>'name',
               'choice_value'=>'id',
               'placeholder' => 'Select city',
               'required'=>false,
             ]);
          };
          
          $builder->get('country')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) use ($formModifier){
              $country=$event->getForm()->getData();
              $formModifier($event->getForm()->getParent(),$country);
            });

    }




    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }

}