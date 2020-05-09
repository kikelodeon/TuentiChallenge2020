
<?php

$host = "52.49.91.111";
$port = 2003;


$message = "HELLO";
echo "Message To server :".$message;

// create socket
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
// connect to server
$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  

// get server response
$result = socket_read ($socket, 1024) or die("Could not read server response\n");
echo "Reply From Server  :". PHP_EOL . ColorResult($result);


$myCurrentGlobalPosition = (object)['x'=>0, 'y'=>0,'value'=>"K"];
$targetPosition = (object)['x'=>1, 'y'=>0,'value'=>"P"];

$gridObj = new Grid();
$index =0;

while(true)
{
	$index ++;
	usleep(1000);
		echo PHP_EOL. "MOVE : " . $index .PHP_EOL;
	$moves = $gridObj->GetMoves($gridObj->GetMatrixRequest($result),$myCurrentGlobalPosition,$targetPosition);
	
	$move = GetBestMove($moves,$gridObj->path,$myCurrentGlobalPosition,$gridObj->alsochecked);


	if($move!=null)
	{	
		$GlobalPoint = (object)['x'=>$move->x + $myCurrentGlobalPosition->x,'y'=>$move->y+$myCurrentGlobalPosition->y];	
		$LocalPoint = (object)['x'=>$move->x,'y'=>$move->y];

		echo "FORWARD".PHP_EOL;
		echo "YourPos: (".$myCurrentGlobalPosition->x.",".$myCurrentGlobalPosition->y.")". PHP_EOL;
		echo "YourMove: (".$LocalPoint->x.",".$LocalPoint->y.")". PHP_EOL;
		echo "YourMove(Global): (".$GlobalPoint->x.",".$GlobalPoint->y.")". PHP_EOL;
		array_push($gridObj->path, $GlobalPoint);	
		$message = 	abs($LocalPoint->x). ($LocalPoint->x>0?"R":"L").abs($LocalPoint->y). ($LocalPoint->y>0?"U":"D");

		$myCurrentGlobalPosition->x =$GlobalPoint->x;
		$myCurrentGlobalPosition->y =$GlobalPoint->y;

	}
	else
	{	
		$removed = array_pop($gridObj->path);
		array_push($gridObj->alsochecked, $removed);	
		$auxindex= (count($gridObj->path)-1);
		$current= $gridObj->path[$auxindex];
		$GlobalPoint = (object)['x'=>$current->x, 'y'=>$current->y];
		$LocalPoint = (object)['x'=>$current->x-$myCurrentGlobalPosition->x,'y'=>$current->y-$myCurrentGlobalPosition->y];		

		echo "BACKWARD".PHP_EOL;
		echo "YourPos: (".$myCurrentGlobalPosition->x.",".$myCurrentGlobalPosition->y.")". PHP_EOL;
		echo "YourMove: (".$LocalPoint->x.",".$LocalPoint->y.")". PHP_EOL;
		echo "YourMove(Global): (".$GlobalPoint->x.",".$GlobalPoint->y.")". PHP_EOL;

		$message = 	abs($LocalPoint->x). ($LocalPoint->x>0?"R":"L").abs($LocalPoint->y). ($LocalPoint->y>0?"U":"D");
			
		$myCurrentGlobalPosition->x =$GlobalPoint->x;
		$myCurrentGlobalPosition->y =$GlobalPoint->y;

	}
	echo PHP_EOL ."MESSAGE: " . $message.PHP_EOL;

		// send string to server
	socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
	// get server response
	$result = socket_read ($socket, 1024) or die("Could not read server response\n");
	echo "Path Length: ". count($gridObj->path).PHP_EOL;
	echo "Reply From Server  :". PHP_EOL . ColorResult($result). PHP_EOL;
		
	if($gridObj->end)
	{$out = str_replace("-", "", $result);
$out = str_replace("Secret key:", "", $out);
		exec("echo '" . trim($out) ."' > testOutput");
		die();
	}
}

function GetBestMove($moves,$path,$myPos, $also)
{
	$acc = 999999999999999;
	$aux = array();

	foreach ($moves as $move) 
	{
		if($move->accuraccy<$acc)
		{
			if(!ExistItem((object)['x'=>$move->x+$myPos->x,'y'=>$move->y+$myPos->y], $path, $also))
			{	
				$acc= $move->accuraccy;		
				array_push($aux, $move);
			}

		}		
	}

	uasort($aux, "cmpAux");
	return count($aux)>0?$aux[count($aux)-1]:null;

}

function cmpAux($a,$b)
{
  if ($a->accuraccy == $b->accuraccy) {

        return 0;

    }
    else
    {
    	return ($a->accuraccy > $b->accuraccy) ? -1 : 1;
    }
}
function ExistItem($point,$path,$also)
{
		foreach ($path as $pp) {

			if($point->x === $pp->x && $point->y === $pp->y)
			{
				return true;
			}
		
		}
		foreach ($also as $pp) {

			if($point->x === $pp->x && $point->y === $pp->y)
			{
				return true;
			}
		
		}
		return false;

}
function ColorResult($result)
{
	$result = str_replace("K", "\e[0;32;40mK\e[0m", $result);
	$result =str_replace("P", "\e[0;31;40mP\e[0m", $result);
	return $result;
}

// close socket
socket_close($socket);
class Grid
{
	public $end =false;
	public $grid = array();
	public $path = array();
	public $alsochecked = array();

	public function GetMatrixRequest($message)
	{
		$linesaux = explode ("\n",$message);
		$lines =array();

	
		$matrix = array();
			foreach ($linesaux as $line) 
		{
			if(strpos($line, "---") ===false && trim($line) !="") 
			{
				array_push($lines, $line);
				 
			}
		}
		$message = implode	("\n",$lines);	
			$message = substr($message, -29);
		$matrixY=2;
		$matrixX=-2;
		foreach ($lines as $line) 
		{
			if(trim($line)!=="")
			{
				$matrixX=-2;
				
				$values =str_split ($line);
				foreach ($values as $value) {					
					array_push($matrix, (object)['x'=>$matrixX, 'y'=> $matrixY,'value'=>$value]);
					$matrixX++;
				}
				$matrixY--;
			
		    }
			
		}
		return $matrix;
	}


	public function GetMoves($matrix,$myCurrentGlobalPosition,$targetPoistion)
	{		
		$moves =array();
		foreach ($matrix as $point) 
		{
			if(($point->x==(2)&&$point->y==(-1)) ||
			 ($point->x==(2)&&$point->y==(1) )||
			 ($point->x==(1)&&$point->y==(-2)) ||
			  ($point->x==(1)&&$point->y==(2))||
			  ($point->x==(-1)&&$point->y==(-2)) ||
			  ($point->x==(-1)&&$point->y==(2)) ||
			  ($point->x==(-2)&&$point->y==(-1)) ||
			  ($point->x==(-2)&&$point->y==(1)))
			{
				if($point->value==".")
				{
					$pointGlobalPos = (object)['x'=>$point->x+$myCurrentGlobalPosition->x,'y'=>$point->y+$myCurrentGlobalPosition->y];
					$accuraccy = $this->GetDistance($pointGlobalPos,$targetPoistion);
					$point->accuraccy =$accuraccy;
					if(!in_array($point,$moves))
						array_push($moves,$point);
				}
				else if(trim($point->value)=="P")
				{
					echo PHP_EOL.PHP_EOL."YOU SAVED THE PRINCESS!!!".PHP_EOL." OMG WHAT A HACKER!".PHP_EOL.PHP_EOL;
					$pointGlobalPos = (object)['x'=>$point->x+$myCurrentGlobalPosition->x,'y'=>$point->y+$myCurrentGlobalPosition->y];
					$accuraccy = $this->GetDistance($pointGlobalPos,$targetPoistion);
					$point->accuraccy =$accuraccy;
					if(!in_array($point,$moves))
						array_push($moves,$point);
					$this->end=true;				
				}
			}		
		}
		return 	$moves ;
	}

	public function GetDistance($vectorA,$vertorB)
	{
		return ceil(sqrt ((($vectorA->x-$vertorB->x)*($vectorA->x-$vertorB->x) )+ (($vectorA->y-$vertorB->y)*($vectorA->y-$vertorB->y))));
	}


}