<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your first name'])
                ]
            ])
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your last name'])
                ]
            ])
            ->add('cin', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your CIN'])
                    // Add Length or Regex constraint for CIN format if needed
                ]
            ])
            ->add('tel', TelType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your telephone number'])
                    // Add Length or Regex constraint for Tel format if needed
                ]
            ])
            ->add('mail', EmailType::class, [ // Use EmailType for validation
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your email'])
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
