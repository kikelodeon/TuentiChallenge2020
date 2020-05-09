<?php
ini_set('memory_limit', '-1');
$lines = file('php://stdin');

$numberOfCases = -1;
$caseIndex = 0;
foreach($lines as $line)
{
	if($numberOfCases == (-1))
	{
		$numberOfCases = intval(trim($line));		
	}
	else
	{
		$caseIndex++;
		$number = trim($line);
		$out =Tuentificate($number);
		echo  "Case #" . $caseIndex . ": " . $out . PHP_EOL;	
	}	

}

function Tuentificate($number)
{
	$originalnmumber = $number;

	if($number<20)
		return "IMPOSSIBLE";

$resto = gmp_mod($number,20);
$steps = gmp_div_q($number, "20", GMP_ROUND_MINUSINF);
$addsteps =$resto>9 && $steps<2?1:0;
if($resto<=9 ||$steps>1 && $resto>9 && $resto<=$steps*9)
{
	return $steps+$addsteps ;
}
else
{
	return "IMPOSSIBLE";
}

}