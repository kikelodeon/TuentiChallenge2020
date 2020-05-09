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
    $elements = explode(" ",trim($line));
    $to = $elements[0];

    $toExclude = count($elements)==1?array(intval($to)+1):array();
      if(count($toExclude)==0)
      {
        for ($i=1; $i <count($elements) ; $i++) { 
          array_push($toExclude, $elements[$i]);
        }
      }
   // echo "TO: ".$to .PHP_EOL;
   // echo "EXCLUDE: ".implode(" ",$toExclude) .PHP_EOL;
    $possibles = array();

    for ($i=1; $i <$to; $i++) { 

      if(!in_array($i, $toExclude))
        array_push($possibles,$i);
    }

$sum = new Summarizer();
$sum->targetNumber = $to;
$sum->validTerms = $possibles;

$result =$sum->CalculateUniqueSumCount();

echo  "Case #" . $caseIndex . ": " . $result . PHP_EOL;  

  }
}


 class Summarizer
    {
      public $targetNumber;
      public $validTerms;

        
        public function TrueForAll($matrix,$condition)
        {
          foreach ($matrix as $m) 
          {
            if(intval($m) !=intval($condition))
              return false;
          }
          return true;
        }

        // Methods.
        public function CalculateUniqueSumCount()
        {
            if (count($this->validTerms) == 0)
                return 0;
            
            $result = 0;
            $baseTerms =array();
            
            $baseTerms[0] = 1;
            for ($i=1; $i <count($this->validTerms) ; $i++) { 
             array_push($baseTerms, 0);
            }

            while (!$this->TrueForAll($baseTerms, 0))
            {
                $sum = 0;
                for ($i = 0; $i < count($baseTerms); $i++)
                {                
                    $sum += $baseTerms[$i] * $this->validTerms[$i];
                }
                
                $orderWasIncreased = false;
                if ($sum >= $this->targetNumber)
                {
                    if ($sum == $this->targetNumber)
                    {
                        $result++;
                    }
                    
                    for ($i = 0; $i < count($baseTerms); $i++)
                    {
                        if ($baseTerms[$i] > 0)
                        {
                            $baseTerms[$i] = 0;
                            if ($i < count($baseTerms) - 1)
                            {
                                $baseTerms[$i + 1]++;                               
                            }
                            
                            $orderWasIncreased = true;
                            break;
                        }
                    }
                }
                
                if (!$orderWasIncreased)
                {
                    $baseTerms[0]++;
                }
            }
            
            return $result;
        }
    }




?>