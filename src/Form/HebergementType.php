<?php
<<<<<<< HEAD
=======
// src/Form/HebergementType.php
>>>>>>> f5842df (Initial commit for Events branch)

namespace App\Form;

use App\Entity\Hebergement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
<<<<<<< HEAD
use Symfony\Component\OptionsResolver\OptionsResolver;
=======
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
>>>>>>> f5842df (Initial commit for Events branch)

class HebergementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
<<<<<<< HEAD
    {
        $builder
            ->add('type')
            ->add('nom')
            ->add('adresse')
            ->add('description')
            ->add('prix_nuit')
            ->add('photo')
        ;
    }
=======
{
    $builder
        ->add('type', TextType::class)
        ->add('nom', TextType::class)
        ->add('adresse', TextType::class)
        ->add('description', TextareaType::class)
        ->add('prix_nuit', NumberType::class)
        ->add('photo', FileType::class, [
            'label' => 'Photo',
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '5000k',
                    'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                    'mimeTypesMessage' => 'Veuillez télécharger une image JPEG, PNG ou GIF valide.',
                ]),
            ],
        ]);
}
>>>>>>> f5842df (Initial commit for Events branch)

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hebergement::class,
        ]);
    }
}
