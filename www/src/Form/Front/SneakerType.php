<?php

namespace App\Form\Front;

use App\Entity\Brand;
use App\Entity\Sneaker;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Form\Front\SneakerImageFormType;

class SneakerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => "Sneaker's title"
                ]
            ])

            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => "Some words about the product"
                ]
            ])

            /*->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                "required" => false
            ])*/

            ->add('brand', EntityType::class, [
                'class' => Brand::class,
                'choice_label' => 'Name',
                'multiple' => false
            ])

            ->add('size', NumberType::class, [
                'attr' => [
                    'placeholder' => "Size"
                ],
            ])

            ->add('price', NumberType::class, [
                'attr' => [
                    'type' => 'number',
                    'placeholder' => "Enter amount($)"
                ]
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => SneakerImageFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => false,
            ])
            ->add('Submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sneaker::class,
        ]);
    }
}
