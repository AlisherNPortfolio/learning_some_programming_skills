<?php
class Kishi
{
    function say()
    {
        echo "Salom, do'stlar!<br>";
    }

    public static function __callStatic($funName, $arguments)
    {
        echo "Siz chaqirgan static metod：" . $funName . "(parameter：" ; 
        print_r($arguments);
        echo ") mavjud emas！<br>\n";
    }
}
$kishi = new Kishi();
$kishi::run("oqituvchi");
$kishi::eat("Alisher", "olma");
$kishi->say();