<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => false,
            ])
            ->add('phone_number', null, [
                'label' => false,
            ])
            ->add('email', null, [
                'label' => false,
            ])
            ->add('number_of_person', null, [
                'label' => false,
            ])
            ->add('date', null, [
                'label' => false,
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('heure_debut', null, [
                'label' => false,
                'widget' => 'single_text',
            ])
            ->add('duration', null, [
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
