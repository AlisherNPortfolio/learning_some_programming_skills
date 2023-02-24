<?php
class Kishi
{
    public $jins;
    public $ism;
    public $yosh;

    public function __construct($ism="",  $yosh=25, $jins='Erkak')
    {
        $this->ism = $ism;
        $this->yosh  = $yosh;
        $this->jins  = $jins;
    }

    public function __clone()
    {
        echo __METHOD__.". siz obyektni klonlayapsiz" . PHP_EOL;
    }

}

$kishi = new Kishi('Alisher');
$kishi2 = clone $kishi;

var_dump('persion1:');
var_dump($kishi);
echo PHP_EOL;
var_dump('kishi2:');
var_dump($kishi2);