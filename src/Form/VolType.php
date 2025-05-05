<?php

namespace App\Form;

use App\Entity\Vol;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('compagnie', TextType::class, [
                'label' => 'Compagnie aérienne',
                'attr' => ['placeholder' => 'Ex: Air France']
            ])
            ->add('aeroport_depart', TextType::class, [
                'label' => 'Aéroport de départ',
                'attr' => ['placeholder' => 'Ex: CDG']
            ])
            ->add('aeroport_arrivee', TextType::class, [
                'label' => 'Aéroport d\'arrivée',
                'attr' => ['placeholder' => 'Ex: JFK']
            ])
            ->add('date_depart', DateType::class, [
                'label' => 'Date de départ',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('date_arrivee', DateType::class, [
                'label' => 'Date d\'arrivée',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix (€)',
                'attr' => ['placeholder' => 'Ex: 299.99']
            ])
            ->add('destination', TextType::class, [
                'label' => 'Destination',
                'attr' => ['placeholder' => 'Ex: New York']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vol::class,
        ]);
    }
}
