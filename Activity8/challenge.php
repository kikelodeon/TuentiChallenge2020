<?php
ini_set('memory_limit', '-1');

$allstring = file_get_contents('https://contest.tuenti.net/resources/2020/resources/img/05-headache.png');
$end = substr($allstring, strpos($allstring, "END")+8, strlen($allstring)); 
$bf  =new Brainfuck();
echo str_replace("THE CODE IS: ","",$bf->decode($end));

class Brainfuck {

        public static function decode($string) {
            $cells = [];            
            $pointer = 0;
            $result = '';
            $sl = strlen($string);
            for ($p = 0; $p < $sl; $p++) {
                $char = $string[$p];
                if ($char === '>') $pointer++;
                if ($char === '<') $pointer--;
                if (!isset($cells[$pointer])) $cells[$pointer] = 0;
                if ($char === '+') $cells[$pointer]++;
                if ($char === '-') $cells[$pointer]--;
                if ($char === '.') $result .= chr($cells[$pointer]);
                if ($char === ']') {
                    $close_pos = $p;
                    if ($cells[$pointer] !== 0) {
                        $other_brackets = 0;
                        while ($string[$p] !== '[') {
                            if ($other_brackets > 0) {
                                $other_brackets--;
                            } else {
                                $p--;
                            }
                            if ($string[$p] === ']') $other_brackets++;
                            if ($p < -255) throw new Exception("Syntax error near ']' at position {$close_pos}: matching bracket not found!");
                        }
                    }
                }
            }
            return $result;
        }
    }
?>