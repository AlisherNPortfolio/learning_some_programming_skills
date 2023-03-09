<?php
class Kishi
{
    public $jins;
    public $ism;
    public $yosh;

    public function __construct($ism="",  $yosh=25, $jins='Male')
    {
        $this->ism = $ism;
        $this->yosh  = $yosh;
        $this->jins  = $jins;
    }

    /**
     * @return array
     */
    public function __sleep() {
        echo "Klassdan tashqarida serialize() funksiyasi ishga tushirilsa __sleep() metodi ishga tushadi";
        $this->ism = base64_encode($this->ism);
        return array('ism', 'yosh');
    }
}

$person = new Kishi('Alisher'); 
echo serialize($person).PHP_EOL;
echo PHP_EOL;