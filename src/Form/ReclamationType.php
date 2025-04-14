<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_reclamation', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'La date est obligatoire.']),
                    new Regex([
                        'pattern' => '/^\d{4}-\d{2}-\d{2}$/',
                        'message' => 'Veuillez entrer une date au format YYYY-MM-DD.',
                    ])
                ],
            ])
            ->add('type', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le type est obligatoire.']),
                    new Regex([
                        'pattern' => '/^[A-Z]/',
                        'message' => 'Le type doit commencer par une lettre majuscule.',
                    ]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le message est obligatoire.']),
                    new Length([
                        'min' => 15,
                        'minMessage' => 'Le message doit contenir au moins 15 caractères.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
