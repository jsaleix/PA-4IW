<?php

namespace App\Form;

use App\Entity\Sneaker;
use App\Entity\Brand;
use App\Entity\Category;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SneakerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => "Titre de la sneaker"
                ]
            ])

            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => "quelques ligne sur le produit"
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
                'choice_label' => 'name',
                'multiple' => false
            ])

            ->add('size', TextType::class, [
                'attr' => [
                    'placeholder' => "taille"
                ]
            ])

            ->add('price', TextType::class, [
                'attr' => [
                    'placeholder' => "taille"
                ]
            ])
            ->add('Valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sneaker::class,
        ]);
    }
}
