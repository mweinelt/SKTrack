<?php
/*
 * sktrack
 * Configuration
 *
 * Author: hexa-
 * File: config.inc.php
 * Created: 26.03.2012, 05:10:02
 */

// mysql settings
$db 			= array(); 
$db["host"]		= "localhost:3306";
$db["user"]		= "sktrack";
$db["pass"]		= "";
$db["db"]		= "sk";

// compensate for timezone differences between mysql and php
$tz_mysql		= "UTC";
$tz_target		= "GMT";

/* Here Be Dragons */

mysql_connect($db["host"], $db["user"], $db["pass"]);
mysql_select_db($db["db"]);

unset($db);
 
?>