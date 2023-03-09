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
    public function __unset($content) {
        echo "Klassdan tashqarida unset() funksiyasini ishlatsak __unset() metodi avtomatik ishga tushadi" . PHP_EOL;
        echo  isset($this->$content) . PHP_EOL;
    }
}

$kishi = new Kishi("Alisher", 25);
unset($kishi->jins);
unset($kishi->ism);
unset($kishi->yosh);