<?php
/*
 * sktrack
 * Configuration
 *
 * Author: hexa-
 * File: config.inc.php
 * Created: 26.03.2012, 05:10:02
 */

$db 			= array(); 
$db["host"]		= "localhost:3306";
$db["user"]		= "sktrack";
$db["pass"]		= "";
$db["db"]		= "sk";


/* Here Be Dragons */

mysql_connect($db["host"], $db["user"], $db["pass"]);
mysql_select_db($db["db"]);

unset($db);
 
?>