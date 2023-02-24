<?php
class Kishi
{
    private $ism;
    private $yosh;

    function __construct($ism="", $yosh=1)
    {
        $this->ism = $ism;
        $this->yosh = $yosh;
    }

    public function __get($propertyName)
    {   
        if ($propertyName == "yosh") {
            if ($this->yosh > 30) {
                return $this->yosh - 10;
            } else {
                return $this->$propertyName;
            }
        } else {
            return $this->$propertyName;
        }
    }
}
$kishi = new Kishi("Alisher", 26);
echo "Name：" . $kishi->ism . PHP_EOL;
echo "Yosh：" . $kishi->yosh . PHP_EOL;