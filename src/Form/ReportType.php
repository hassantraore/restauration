<?php

namespace App\Form;

use App\Entity\Month;
use App\Entity\Report;
use App\Entity\Year;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('year', EntityType::class, [
            // looks for choices from this entity
            'class' => Year::class,

            // uses the User.username property as the visible option string
            'choice_label' => 'label',
        ])
        ->add('month', EntityType::class, [
            // looks for choices from this entity
            'class' => Month::class,

            // uses the User.username property as the visible option string
            'choice_label' => 'label',
               ])
            ->add('exploitation_product', CollectionType::class, [
                'entry_type' => DepenseType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])
            ->add('exploitation_charge', CollectionType::class, [
                'entry_type' => DepenseType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])

            ->add('financial_product', CollectionType::class, [
                'entry_type' => DepenseType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])
            ->add('financial_charge', CollectionType::class, [
                'entry_type' => DepenseType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])

            ->add('no_current_product', CollectionType::class, [
                'entry_type' => DepenseType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])
            ->add('no_current_charge', CollectionType::class, [
                'entry_type' => DepenseType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])

            ->add('impot', NumberType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Report::class,
        ]);
    }
}
