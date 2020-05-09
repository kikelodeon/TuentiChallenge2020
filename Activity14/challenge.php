
<?php

$host = "52.49.91.111";
$port = 2092;


$message = "HELLO";
echo "Message To server :".$message;

// create socket
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
// connect to server
$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  

// get server response
$result = socket_read ($socket, 1024) or die("Could not read server response\n");
echo "Reply From Server  :". PHP_EOL . $result;

die();
