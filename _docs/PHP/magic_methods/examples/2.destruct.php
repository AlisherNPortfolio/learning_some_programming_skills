<?php
class Kishi
{

    public $ism;
    public $yosh;
    public $jins;

    public function __construct($ism = "", $jins = "Erkak", $yosh = 22)
    {
        $this->ism = $ism;
        $this->jins  = $jins;
        $this->yosh  = $yosh;
    }

    /**
     * say method
     */
    public function say()
    {
        echo "Ism：" . $this->ism . ",Jins：" . $this->jins . ",Yosh：" . $this->yosh;
    }

    /**
     * declare a destructor method
     */
    public function __destruct()
    {
        echo "Xo'sh, mening ismim " . $this->ism;
    }
}

$kishi = new Kishi("Alisher");
unset($kishi); // $kishi obyektini o'chirish
