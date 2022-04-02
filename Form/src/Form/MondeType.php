<?php

namespace App\Form;

use App\Entity\Monde;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MondeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('continent',ChoiceType::class, [
                'choices'  => [
                    'AFRICAIN' => "AFRICAIN",
                    'EUROPEIN' => "EUROPEIN",
                    'ASIE' => "ASIE",
                ],
               'placeholder'=>'Select continents...'
                ]
            )
            ->add('pays',ChoiceType::class, [
                'choices'  => [
                    'Madagascar' => "Madagascar",
                    'Paris' => "Paris",
                    'Chine' => "Chine",
                ],
                'expanded'=>true,
                'multiple'=>true,
                ])
            ->add('sexe',ChoiceType::class, [
                'choices'  => [
                    'Home' => "Home",
                    'Femme' => "Femme",
                ],
                'expanded'=>true,
                'multiple'=>false,
                'required'=>true,
                ])
            ->add('ville',ChoiceType::class, [
                'placeholder'=>'Ville...'
                ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Monde::class,
        ]);
    }
}
