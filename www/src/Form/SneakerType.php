<?php

namespace App\Form;

use App\Entity\Sneaker;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
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

            ->add('brand', ChoiceType::class, [
                'attr' => [
                    'placeholder' => "marque"
                ]
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
