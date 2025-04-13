<?php

namespace App\Form;

use App\Entity\Hebergement;
use App\Entity\ReservationHebergement;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationHebergementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_debut', null, [
                'widget' => 'single_text',
            ])
            ->add('date_fin', null, [
                'widget' => 'single_text',
            ])
            ->add('nb_perso')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('hebergement', EntityType::class, [
                'class' => Hebergement::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationHebergement::class,
        ]);
    }
}
