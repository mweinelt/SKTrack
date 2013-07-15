<?php
/*
 * sktrack
 * SKTrack Index
 *
 * Author: hexa-
 * File: index.php
 * Created: 26.03.2012, 03:22:52
 */

require("lib/session.inc.php");
require("lib/functions.inc.php");

header('content-type: text/html; charset=utf-8');

/*******************
 * List-Handling
 *******************/

$query = "SELECT id,title,active_raid FROM sk_lists";
$result = mysql_query($query);

$lists = array();
while ($row = mysql_fetch_assoc($result))
{
	$lists[$row['id']] = array("title" => $row['title'],
							   "active_raid" => $row['active_raid']);
}
$smarty->assign("lists", $lists);

// remember list/raid id
$_SESSION['list_sel'] = (isset($_REQUEST['list']))?  intval($_REQUEST['list']) : 1;

if (isset($lists[$_SESSION['list_sel']]['active_raid']))
	$_SESSION['raid_sel'] = $lists[$_SESSION['list_sel']]['active_raid'];

// export current list to template
if (isset($_SESSION['list_sel']))
	$smarty->assign("list_sel", $_SESSION['list_sel']);
	
if (isset($_SESSION['raid_sel']))
	$smarty->assign("raid_sel", $_SESSION['raid_sel']);

/*******************
 * Template-Handling
 *******************/

// select template
if (isset($_SESSION['list_sel']))
{
	if (!isset($_SESSION['auth']))
	{
	// 	No Authentication, fall back to read-only stats 
		require("lib/tab_log.php");
		$template = "tab_log.tpl";
	} else {
	// 	Authentication OK!
		if (is_numeric($_SESSION['raid_sel']) && $_SESSION['raid_sel'] != -1)
		{
		// 	Loot Distribution
			require("lib/tab_loot_distribution.php"); 
			
			$template = "tab_loot_distribution.tpl";
		} else {
		// 	Raid Selection	
			include("lib/tab_user_selection.php");
				
			$template = "tab_user_selection.tpl";
		}
	}
} else {
	
	unset($template);
	
}

// assign template variable
if (isset($template))
{
	
	$smarty->assign("template", $template);
	
} else {
	
	$smarty->assign("content", "Keine (g&uuml;ltige) Liste ausgew&auml;hlt.");
	
}

// exit mysql

mysql_close();

// generic template thingys

$smarty->assign("sk", $sk);

$smarty->display('structure.tpl');
 
?>
