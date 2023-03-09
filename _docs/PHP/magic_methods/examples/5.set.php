<?php
class Kishi
{
    private $ism;
    private $yosh;

    public function __construct($ism="",  $yosh=25)
    {
        $this->ism = $ism;
        $this->yosh  = $yosh;
    }

    public function __set($property, $value) {
        if ($property=="yosh")
        {
            if ($value > 150 || $value < 0) {
                return;
            }
        }
        $this->$property = $value;
    }

    public function say(){
        echo "Mening ismim ".$this->ism.", Men ".$this->yosh." yoshdaman";
    }
}

$Person=new Kishi("Alisher", 26);
$Person->ism = "Abdusalom";
$Person->yosh = 16;
$Person->yosh = 160;
$Person->say();