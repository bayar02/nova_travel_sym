<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Nom is required']),
                    new Assert\Length(['min' => 2]),
                ],
            ])
            ->add('prenom', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Prenom is required']),
                    new Assert\Length(['min' => 2]),
                ],
            ])
            ->add('cin', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'CIN is required']),
                    new Assert\Regex([
                        'pattern' => '/^\d{8}$/',
                        'message' => 'CIN must be exactly 8 digits.',
                    ]),
                ],
            ])
            ->add('tel', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Telephone is required']),
                    new Assert\Regex([
                        'pattern' => '/^(\+?\d{1,3})?\d{8,12}$/',
                        'message' => 'Invalid phone number format.',
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                ],
                'multiple' => true,
                'expanded' => true, // use checkboxes
                'label' => 'Roles'
            ])
            ->add('mail', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Email is required']),
                    new Assert\Email(['message' => 'Invalid email address']),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Password is required']),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Password must be at least {{ limit }} characters long.',
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
