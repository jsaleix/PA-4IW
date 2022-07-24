<?php

namespace App\Form;

use App\Datas\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'crsf_production' => false
        ]);
    }
}