<?php
$lines = file('php://stdin');
$nVal = array();
$mVal = array();
$outIndex = 1;
$out ="";
foreach($lines as $line)
{
	if(!is_numeric(trim($line)))
	{
		$case = trim($line);
		$result = GetWinner($case);
		$out .=  "Case #" . $outIndex . ": " . $result . PHP_EOL;
		$outIndex++;
	}
}
echo $out;

function GetWinner($case)
{
	if(strpos($case, "R")!==false && strpos($case, "P")!==false )
	{
		return "P";
	}
	else if(strpos($case, "R")!==false && strpos($case, "S")!==false )
	{
		return "R";
	}
	else if(strpos($case, "P")!==false && strpos($case, "S")!==false )
	{
		return "S";
	}
	else
	{
		return "-";
	}		
}
?>