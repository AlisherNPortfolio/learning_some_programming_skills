<?php
class Kishi
{
    public $jins;
    private $ism;
    private $yosh;

    public function __construct($ism="",  $yosh=25, $jins='Erkak')
    {
        $this->ism = $ism;
        $this->yosh  = $yosh;
        $this->jins  = $jins;
    }

    /**
     * @param $content
     *
     * @return bool
     */
    public function __isset($content) {
        echo "{$content} xususiyati private，__isset() metodi avtomatik ishga tushadi." . PHP_EOL;
        echo  isset($this->$content);
    }
}

$kishi = new Kishi("Alisher", 25);
echo isset($kishi->jins) . PHP_EOL;
echo isset($kishi->ism) . PHP_EOL;
echo isset($kishi->yosh) . PHP_EOL;