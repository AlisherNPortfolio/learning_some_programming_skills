<?php
class Kishi
{                             
    function say()
    {
           echo "Salom, do'stlar!<br>";
    }     

    function __call($funName, $arguments)
    {
          echo "Siz chaqirgan metod：" . $funName . "(parameter" ;  
          print_r($arguments); 
          echo ") mavjud emas!！<br>\n";                   
    }                                         
}
$kishi = new Kishi();           
$kishi->run("oqituvchi");
$kishi->eat("Alisher", "olma"); 
$kishi->say();