<?php
/*
 * sktrack
 * Log Template Assistant
 *
 * Author: hexa-
 * File: tab_log.php
 * Created: 26.03.2012, 06:31:03
 */

/* Content:
 * - Ranking
 * - Item Log
 */

if (!isset($_SESSION['list_sel']))
	echo("Ouch! Missing List Selection for Loot Distribution.");

/*******************
 * Ranking
 *******************/

// get current positions on list
$query = "SELECT sk_list_position.*,sk_users.username as name
		  FROM sk_list_position
		  INNER JOIN sk_users ON sk_list_position.user_id = sk_users.id
		  WHERE list_id = ".$_SESSION['list_sel']."
		  ORDER BY position ASC";
$result = mysql_query($query);

$pool = array(); // raid 
while ($row = mysql_fetch_array($result))
	$pool[] = array("pos" => $row['position'],
					"name" => $row['name'],
					"uid" => $row['user_id'],
					"active" => $row['raid_id'] != -1 ? true : false);

$smarty->assign("pool", $pool);

/*******************
 * Item Log
 *******************/

// latest raids
$query = "SELECT id FROM sk_raids ORDER BY start DESC LIMIT 10";
$result = mysql_query($query);

$raids = array();
while ($row = mysql_fetch_array($result))
	$raids[] = $row['id'];

// latest items from the according raids
$items = array();

if (mysql_num_rows($result) > 0)
{
	$query = "SELECT * FROM sk_item_log
		  	INNER JOIN sk_users ON sk_item_log.user_id = sk_users.id
		  	INNER JOIN sk_raids ON sk_item_log.raid_id = sk_raids.id
		  	INNER JOIN sk_items ON sk_item_log.item_id = sk_items.id
		  	WHERE sk_item_log.raid_id IN (".implode(",", $raids).")
		  	ORDER BY date DESC";
	$result = mysql_query($query);		
	
	
	while ($row = mysql_fetch_array($result))			
		$items[$row['raid_id']][] = array("username" => $row['username'],
					 				  	"item_id" => $row['item_id'],
					 				  	"item_quality" => $row['quality'],
					 				  	"item_name" => $row['name'],
					 				  	"lootmode" => lootmode($lootmode, $row['pos_old'], $row['pos_new']),
					 				  	"loottime" => timetostr(strtotime($row['date'])),
					 				  	"raid_title" => $row['title'],
					 				  	"raid_start" => timetostr(strtotime($row['start'])),
					 				  	"raid_end" => date("G:i", strtotime($row['end'])));
	
}
	
$smarty->assign("items", $items);

?>