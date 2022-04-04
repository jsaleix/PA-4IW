<?php

namespace App\Tests;

class Demo
{
    private $demo;
    
    public function setDemo($demo){
        $this->demo = $demo;
    }
    public function getDemo(){
        return $this->demo;
    }
    public function __construct()
    {
    }
}