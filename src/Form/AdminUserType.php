<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'required' => false, // Or adjust based on your User entity constraints
            ])
            ->add('nom', TextType::class, [
                'required' => false,
            ])
            ->add('cin', TextType::class, [
                'required' => false,
            ])
            ->add('tel', TextType::class, [
                 'label' => 'Telephone', // Customize label
                 'required' => false,
            ])
            ->add('mail', EmailType::class, [
                 'label' => 'Email',
            ])
            // Add roles field - make sure roles are simple strings in DB or use DataTransformer
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                    // Add other roles as needed
                ],
                'multiple' => true, // Allow multiple roles
                'expanded' => true, // Render as checkboxes
                'required' => true,
                'label' => 'Roles',
            ])
            // Exclude plainPassword and other sensitive fields
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
} 