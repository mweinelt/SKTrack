<?php


/*
 * sktrack
 * Session Handler
 *
 * Author: hexa-
 * File: session.inc.php
 * Created: 26.03.2012, 04:16:46
 */

session_start();

require_once ("lib/config.inc.php");

require_once ("dep/smarty/libs/Smarty.class.php");
$smarty= new Smarty();

// $smarty->testInstall();

require_once("lib/handler.inc.php");

if (!isset ($_SESSION['auth']))
	$smarty->assign("logged_in", false);
else
{
	$smarty->assign("logged_in", true);
	$smarty->assign("auth_name", $_SESSION['auth_name']);
}

?>