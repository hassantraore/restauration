<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Plat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PlatType extends AbstractType
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
            ->add('description')
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',
            ])
            /* ->add('price') */
            ->add('size', CollectionType::class, [
                'entry_type' => SizeType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])
            ->add('isAvailable')
            ->add('promotion')
            ->add('ingredient', EntityType::class, [
                // looks for choices from this entity
                'class' => Ingredient::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
            ])

            ->add('sauce', CollectionType::class, [
                'entry_type' => SauceType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}
