<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez entrer votre prénom']),
                    new Assert\Length(['min' => 2, 'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères']),
                ]
            ])
            ->add('nom', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez entrer votre nom']),
                    new Assert\Length(['min' => 2, 'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères']),
                ]
            ])
            ->add('cin', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez entrer votre CIN']),
                    new Assert\Regex([
                        'pattern' => '/^\d{8}$/',
                        'message' => 'Le CIN doit contenir exactement 8 chiffres',
                    ])
                ]
            ])
            ->add('tel', TelType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez entrer votre numéro de téléphone']),
                    new Assert\Regex([
                        'pattern' => '/^\d{8}$/',
                        'message' => 'Le numéro de téléphone doit contenir exactement 8 chiffres',
                    ])
                ]
            ])
            ->add('mail', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez entrer votre adresse email']),
                    new Assert\Email(['message' => 'Adresse email invalide']),
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez entrer un mot de passe']),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                    new Assert\Regex([
                        'pattern' => '/(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&\-_#])/',
                        'message' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
