<?php

class A
{
    public $one = '';
    public $two = '';

    public function __construct()
    {
    }

    public function echoOne()
    {
        echo $this->one . '\n';
    }

    public function echoTwo()
    {
        echo $this->two . '\n';
    }
}

// klassdan obyekt olamiz
$a = new A();

// reflection obyektini olamiz
$reflector = new ReflectionClass('A');

// reflection obyektning getProperties metodi bilan A klasning xususiyat va metodlarini olamiz
$properties = $reflector->getProperties();

$i = 1;

foreach ($properties as $property) {
    // $a obyekt xususiyatiga qiymat beramiz
    $a->{$property->getName()} = $i;

    // $a obyekt metodini chaqirib ishlatamiz
    $a->{"echo" . ucfirst($property->getName())}() . '\n';
    $i++;
}
