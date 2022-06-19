<?php

namespace App\Form;

use App\Entity\Extras;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ExtrasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image (JPG or PNG file)',
                'required' => false,
                'allow_delete' => true,
                //'delete_label' => 'delete',
                //'download_label' => 'download',
                'download_uri' => false,
                //'image_uri' => true,
                //'asset_helper' => true,
                'imagine_pattern' => 'squared_thumbnail_medium',
            ])
            ->add('name')
            ->add('price')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Extras::class,
        ]);
    }
}
