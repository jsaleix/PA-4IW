<?php

namespace App\Form\Front;

use App\Datas\SearchData;
use App\Entity\Brand;
use App\Entity\Sneaker;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Search a sneakers'
                ]
            ])

            ->add('categories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Brand::class,
                'expanded' => true,
                'multiple' => true
            ])

            ->add('min', NumberType::class,[
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Minimal price'
                ]
            ])

            ->add('max', NumberType::class,[
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Maximal price'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'crsf_production' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}