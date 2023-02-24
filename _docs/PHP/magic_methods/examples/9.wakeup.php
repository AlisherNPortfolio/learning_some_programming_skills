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

    /**
     * @return array
     */
    public function __sleep() {
        echo "Klassdan tashqarida serialize() funksiyasi ishga tushirilsa __sleep() metodi ishga tushadi" . PHP_EOL;
        $this->ism = base64_encode($this->ism);
        return array('ism', 'yosh');
    }

    /**
     * __wakeup
     */
    public function __wakeup() {
        echo "Klassdan tashqarida unserialize() funksiyasi ishga tushirilsa __wakeup() metodi ishga tushadi" . PHP_EOL;
        $this->ism = 2;
        $this->jins = 'Erkak';
    }
}

$kishi = new Kishi('Alisher');
var_dump(serialize($kishi));
var_dump(unserialize(serialize($kishi)));