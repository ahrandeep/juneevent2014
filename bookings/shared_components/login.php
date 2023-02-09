<?php // login.php

$db_hostname = 'localhost';
$db_database = 'reservations';
$db_username = 'root';
$db_password = 'root';
/*
$db_hostname = 'localhost';
$db_database = 'pemball';
$db_username = 'pemball';
$db_password = 'TebAtPadd';*/


// Connect to the mySQL server.
$db_server = mysql_connect($db_hostname, $db_username, $db_password);
if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

// Load the reservations database.
mysql_select_db($db_database, $db_server)
or die("Unable to select database: " . mysql_error());

?>