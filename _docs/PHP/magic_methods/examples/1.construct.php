<?php
class Kishi
{
    public $ism;
    public $yosh;
    public $jins;

    public function __construct($ism = "Alisher", $jins = "Erkak", $yosh = 22)
    {
        $this->ism = $ism;
        $this->jins = $jins;
        $this->yosh = $yosh;
    }

    /**
     * say method
     */
    public function say()
    {
        echo "Ism：" . $this->ism . ",Jins：" . $this->jins . ",Yosh：" . $this->yosh;
    }
}

$kishi = new Kishi();
echo $kishi->say();
