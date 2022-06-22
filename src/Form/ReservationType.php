<?php

namespace App\Form;

use App\Entity\Reservation;
use libphonenumber\PhoneNumberFormat;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => false,
                'constraints' => [
                    new NotBlank(null, 'Le nom est obligatoire'),
                ],
            ])
            ->add('phone_number', PhoneNumberType::class, ['default_region' => 'MA', 'format' => PhoneNumberFormat::NATIONAL], [
                'label' => false,
                'constraints' => [
                    new NotBlank(null, 'Le numero de téléphone est obligatoire'),
                ],
            ])
            ->add('email', null, [
                'label' => false,
                'constraints' => [
                    new NotBlank(null, 'Veuillez rentrer un email valide'),
                    new Email(null, 'Veuillez rentrer un email valide'),
                ],
            ])
            ->add('number_of_person', null, [
                'label' => false,
                'constraints' => [
                    new NotBlank(null, 'Veuillez rentrer le nombre de personne'),
                ],
            ])
            ->add('duration', null, [
                'label' => false,
                'constraints' => [
                    new NotBlank(null, 'Veuillez rentrer la durée de reservation'),
                ],
            ])
            ->add('date_debut', null, [
                'label' => false,
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'constraints' => [
                    new GreaterThan([
                        'value' => 'today +1 days',
                        'message' => "La date doit etre supérieure a celle d'aujourd'hui",
                    ]),
                    new NotBlank(null, 'Veuillez rentrée la date et l\'heure de reservation'),
                ],
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
