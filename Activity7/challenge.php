<?php
ini_set('memory_limit', '-1');
$lines = file('php://stdin');

$file ="";
$numberOfCases =trim($lines[0]);
unset($lines[0]);

$file = trim(implode("", $lines));

$qwerty= "! #$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~";
$dvorak= "!_#$%&-()*}w[vz0123456789SsW]VZ@AXJE>UIDCHTNMBRL#POYGK<QF:/\=^{`axje.uidchtnmbrl'poygk,qf;?|+~";
$quertyArray= str_split($qwerty);
$dvorakArray= str_split($dvorak);

$dictionary = array();
for ($i=0; $i <count($dvorakArray)-1 ; $i++) { 
	$dictionary[$dvorakArray[$i]] = $quertyArray[$i];
}
$out= strtr( $file,$dictionary);
$out = explode("\n", $out);
$index=0;
foreach ($out as $case) {
	$index++;
			echo  "Case #" . $index . ": " . $case . PHP_EOL;	
}





?>