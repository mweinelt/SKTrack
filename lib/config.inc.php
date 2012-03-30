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
$tz			= array();
$tz['mysql']		= "UTC";
$tz['target']		= "GMT";

// general setup
$sk			= array();
$sk['title'] 		= "Suicide Kings";
$sk['locale']		= "de_DE";


/* Here Be Dragons */

setlocale(LC_ALL, $sk['locale']);

mysql_connect($db["host"], $db["user"], $db["pass"]);
mysql_select_db($db["db"]);

unset($db);
 
?>