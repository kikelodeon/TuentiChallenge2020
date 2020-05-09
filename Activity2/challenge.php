<?php
ini_set('memory_limit', '-1');
$lines = file('php://stdin');

$caseIndex = 0;
$caseResult = 0;
$numberOfCases = -1;
$winners = array();
$loosers = array();
$elements=0;

foreach($lines as $line)
{
	if($numberOfCases == (-1))
	{
		$numberOfCases = intval(trim($line));
	}
	else
	{
		$a =preg_match_all('/\S+/', $line, $matches, PREG_PATTERN_ORDER);
		
		if(count($matches[0])==1)
		{
			$caseIndex++;
			$winners = array();
			$loosers = array();
			$elements = intval($matches[0][0]);		
		}
		else
		{
			$winnerIndex = intval($matches[0][2]) ==1?0:1;
			$looserIndex = intval($matches[0][2]) ==1?1:0;
			$winner = $matches[0][$winnerIndex];
			$looser = $matches[0][$looserIndex];
			array_push($winners, $winner);	
			array_push($loosers, $looser);	
			$elements--;
			if($elements==0)
			{
				$winners = array_count_values($winners);
				$loosers = array_count_values($loosers);
				$best = (object)['player'=>'none','score'=>(-1000)];

				foreach ($winners as $key => $value) {
					$player = $key;
					$looses = isset($loosers[$key])?intval($loosers[$key]):0;
					$score = intval($value) -$looses;
					if($score >$best->score)
					{
						$best->player = $player;
						$best->score = $score;
					}
				}
				echo  "Case #" . $caseIndex . ": " . $best->player . PHP_EOL;		
			}		
		}
	}	

}

?>