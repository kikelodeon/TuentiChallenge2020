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

		$url = "https://contest.tuenti.net/resources/2020/resources/pg17013.txt";
		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
		$raw = curl_exec( $ch );

		$file = str_replace("Ú", "ú",$raw);
		$file = str_replace("Ó", "ó",$file);
		$file = str_replace("Í", "í",$file);
		$file = str_replace("Á", "á",$file);
		$file = str_replace("É", "é",$file);
		$file = str_replace("Ü", "ü",$file);
		$file = str_replace("Ñ", "ñ",$file);
		$file = strtolower($file);

		$file =  preg_replace('/((?![abcdefghijklmnñopqrstuvwxyzáéíóúü ]).)/iu', " ", $file);
		preg_match_all('/\S+/iu', $file, $matches, PREG_PATTERN_ORDER);

		$rankingaux = array();
		foreach ($matches[0] as $match) {

			if(mb_strlen($match)>2)
			{
				array_push($rankingaux, $match);
			}
		}
		$ranking = array_count_values($rankingaux);

		$dictionary = array();
		foreach ($ranking as $key => $value) {
		array_push($dictionary, (object)['word'=>$key, 'score'=>$value]);	
		}
		uasort($dictionary, 'cmp');

	}
	else
	{
		$caseIndex++;


		$case = trim($line);
		$out="";
		if(is_numeric($case))
		{
			$result = GetElementByIndex($dictionary,intval($case)-1);
			$out  = $result->word . " " .  $result->score;
		}	
		else
		{
			$result = GetElementByWord($dictionary,$case);

			$out  = $result->score . " #" . $result->index;
		}
		echo  "Case #" . $caseIndex . ": " . $out . PHP_EOL;	
	}	

}
function cmp($a, $b) {
    if ($a->score == $b->score) {

        return strnatcmp($a->word,$b->word);

    }
    else
    {
    	return ($a->score > $b->score) ? -1 : 1;
    }
   
}

function GetElementByWord($dictionary,$key)
{
	$index =1;
	foreach ($dictionary as $var) {
		if(trim($var->word) == trim($key))
		{
			$out = (object)['word'=>$var->word,'score'=>$var->score, 'index'=>$index];
			
			return $out;
		}
		$index++;
	}
	return null;
}


function GetElementByIndex($dictionary,$index)
{
	$i =0;
	foreach ($dictionary as $var) {
		if($index ==$i)
		{
			$out = (object)['word'=>$var->word,'score'=>$var->score, 'index'=>$index+1];			
			return $out;
		}
		$i++;
	}
	return null;
}

function DebugDictionary($dictionary)
{
	$i =0;
	foreach ($dictionary as $var) {
		
			echo 'word '. $var->word . '      score '. $var->score. '          index '. $i . PHP_EOL;			
		
		$i++;
	}
}
?>