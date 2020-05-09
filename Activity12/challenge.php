<?php
ini_set('memory_limit', '-1');
$lines = file('php://stdin');
$text1URL = "/plaintexts/test1";
$text2URL = "/plaintexts/test2";
$key1URL = "/ciphered/test1";
$key2URL = "/ciphered/test2";

$text1= file_get_contents($text1URL);
$text2= file_get_contents($text2URL);
$key1= file_get_contents($key1URL);
$key2= file_get_contents($key2URL);

$text1Value = StrToDec($text1);
$text2Value = StrToDec($text2);
$key1Value = StrToDec($key1);
$key2Value = StrToDec($key2);

$e = 65537;

$num1A = bcpow($text1Value,$e);
$num1B = $key1Value;
$diff1 = bcsub($num1A, $num1B);

$num2A = bcpow($text2Value,$e);
$num2B = $key2Value;
$diff2 = bcsub($num2A, $num2B);

$mod = GetGCD($diff1,$diff2);


function StrToDec($str)
{
  return binary_to_decimal(strigToBinary($str));
}

function binary_to_decimal($a) {
    $bin_array = str_split($a);

    $y=sizeof($bin_array)-1;
    for ($x=0; $x<sizeof($bin_array)-1; $x++) {
        if ($bin_array[$x] == 1) {
            $bin_array[$x] = bcpow(2, $y);
        }
        $y--;
    }
   
    for ($z=0; $z<sizeof($bin_array); $z++) {
        $result = bcadd($result, $bin_array[$z]);
    }
    echo $result;
}



function strigToBinary($string)
{
    $characters = str_split($string);
 
    $binary = [];
    foreach ($characters as $character) {
        $data = unpack('H*', $character);
        $binary[] = base_convert($data[1], 16, 2);
    }
 
    return implode(' ', $binary);    
}
 
function binaryToString($binary)
{
    $binaries = explode(' ', $binary);
 
    $string = null;
    foreach ($binaries as $binary) {
        $string .= pack('H*', dechex(bindec($binary)));
    }
 
    return $string;    
}



function GetGCD($a,$b)
{
    $temporal=0;
    //1- Hacer que el valor a sea el mayor 
  if(bccomp($a,$b)==(-1))
  { 
    $temporal=$a;
    $a=$b;
    $b=$temporal;
  }
  //2- Si el resto es igual a 0 termina el algoritmo 
  while(bccomp($b,0)!=0)
  {
    //3-Calcular el resto de dividir a y b 
    $resto= bcmod($a, $b);
    //4-Asignar el valor más pequeño a a 
    $a=$b;
    //5-Asignar el resto a b 
    $b=$resto;
  }
  return $a;  
}


?>