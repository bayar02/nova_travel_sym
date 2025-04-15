<?php

namespace App\Form;

use App\Entity\Hebergement;
use App\Entity\ReservationHebergement;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;



class ReservationHebergementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date_debut', DateType::class, [
            'widget' => 'single_text',
            'constraints' => [
                new NotBlank(['message' => 'La date de début est obligatoire.']),
                new GreaterThanOrEqual([
                    'value' => new \DateTime('today'),
                    'message' => 'La date de début doit être aujourd\'hui ou une date future.',
                ]),
            ],
        ])
            ->add('date_fin', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'La date de fin est obligatoire.']),
                ],
            ])
            ->add('nb_perso', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nombre de personnes est obligatoire.']),
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'Le nombre de personnes doit être supérieur à 0.',
                    ]),
                ],
            ])

            ->add('hebergement', EntityType::class, [
                'class' => Hebergement::class,
                'choice_label' => 'nom', // Or any other property
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationHebergement::class,
        ]);
    }
}
