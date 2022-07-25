<?php

namespace App\Datas;

use App\Entity\Brand;
use App\Entity\Sneaker;

class SearchData
{

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Sneaker[]
     */
    public $categories = [];

    /**
     * @var null|integer
     */
    public $min;

    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|bool
     */
    public $fromShop;

}