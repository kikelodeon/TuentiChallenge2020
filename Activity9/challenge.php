<?php
ini_set('memory_limit', '-1');

$finalFrom= "3A3A333A333137393D39313C3C3634333431353A37363D";



$toHex ="3633363A33353B393038383C363236333635313A353336";

$toString = HexToString($toHex);
$fromString = "514;248;980;347;145;332";

$fromBytesArray = StringToBytesArray($fromString);

$toBytesArray= StringToBytesArray($toString);

$key = XorBytes($fromBytesArray,$toBytesArray);

$resultA = XorBytes($key,StringToBytesArray(HexToString($finalFrom)));
echo BytesArrayToString($resultA);
die();

function Encrypt($key,$message)
{
    $encrypted = "";

    for ($i=0; $i <strlen($message); $i++) { 

       $c = $message[$i];
       $asc_char = ord($c);
       $key_pos= strlen($key)-1-$i;

        $key_char =$key[$key_pos];
        $crpt_chr = $asc_char ^ ord($key_char);
        $hx_crpt_chr = dechex($crpt_chr);
        $encrypted .= $hx_crpt_chr;
    }
       return $encrypted;
}

function XorBytes($byteArrayA,$byteArrayB)
{
    $bytes = array();
    for ($i=0; $i <count($byteArrayA) ; $i++) { 
       array_push($bytes, $byteArrayA[$i] ^ $byteArrayB[$i]);
    }
    return $bytes;
}


function StringToBytesArray($string)
{
    $array = str_split($string);
    $fromOrd = array();
    foreach ($array as $char ) {
        array_push($fromOrd, ord($char));
    }
    return $fromOrd;
}

function BytesArrayToString($bytesArray)
{
    $string= "";
    foreach ($bytesArray as $byte ) {
       $char = chr($byte);
       $string .= $char;
    }
    return $string;
}

function StringToHex($string){
    $hex = '';
    for ($i=0; $i<strlen($string); $i++){
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $hex .= substr('0'.$hexCode, -2);
    }
    return strToUpper($hex);
}
function HexToString($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}

?>