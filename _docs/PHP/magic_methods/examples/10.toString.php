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

    public function __toString()
    {
        return  'Ketdik, ketdik, ketdik';
    }
}

$kishi = new Kishi('Alisher');
echo $kishi;