<?php
//Previously i get the ip of of the solution and set in my hosts file [IP] pre.steam-origin.contest.tuenti.net
//Then:
$res = json_decode(shell_exec('curl -s pre.steam-origin.contest.tuenti.net:9876/games/cat_fight/get_key'));

echo $res->key;
?>
