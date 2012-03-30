<?php
/*
 * sktrack
 * Loot Distribution Template Assistant
 *
 * Author: hexa-
 * File: tab_loot_distribution.php
 * Created: 26.03.2012, 06:31:03
 */

/* Content:
 * - Raid Metadata
 * - Raid List
 * - Item Log (raid)
 */
 
if (!isset($_SESSION['list_sel']))
	die("Ouch! Missing List Selection for Loot Distribution.");
 
/*******************
 * Raid Metadata
 *******************/
 
$query = "SELECT * FROM sk_raids WHERE id = ".$lists[$_SESSION['list_sel']]['active_raid']."";
$result = mysql_query($query);

$row = mysql_fetch_assoc($result);

$smarty->assign("raid_title", $row['title']);
$smarty->assign("raid_start", date("d. F Y, G:i", strtotime($row['start']))." Uhr");	
 
 
/*******************
 * Raid List Order
 *******************/

// get current positions on list
$query = "SELECT sk_list_position.*,sk_users.username as name
		  FROM sk_list_position
		  INNER JOIN sk_users ON sk_list_position.user_id = sk_users.id
		  WHERE list_id = ".$_SESSION['list_sel']."
		  AND raid_id > 0
		  AND raid_id = ".$_SESSION['raid_sel']."
		  ORDER BY position ASC";
$result = mysql_query($query);

$raid = array(); // raid 
while ($row = mysql_fetch_assoc($result))
	$raid[] = array("pos" => $row['position'],
					"name" => $row['name'],
					"uid" => $row['user_id']);

$smarty->assign("raid", $raid);

/*******************
 * Item Log (raid)
 *******************/
 
// latest items
$query = "SELECT * FROM sk_item_log
		  INNER JOIN sk_users ON sk_item_log.user_id = sk_users.id
		  INNER JOIN sk_raids ON sk_item_log.raid_id = sk_raids.id
		  INNER JOIN sk_items ON sk_item_log.item_id = sk_items.id
		  WHERE sk_item_log.raid_id = ".$_SESSION['raid_sel']."
		  ORDER BY date DESC
		  LIMIT 5";
$result = mysql_query($query);

$items = array();
$i = 0;
while ($row = mysql_fetch_assoc($result))
{
	$lootTime = strftime("%H:%M", handleTZ($row['date']));
	$raidStart = timetostr(handleTZ($row['start']));
	
	$items[] = array("username" => $row['username'],
					 "item_id" => $row['item_id'],
					 "item_quality" => $row['quality'],
					 "item_name" => $row['name'],
					 "lootmode" => lootmode($row['lootmode'], $row['pos_old'], $row['pos_new']),
					 "loottime" => $lootTime,
					 "raid_title" => $row['raidtitle'],
					 "raid_id" => $row['raid_id'],
					 "raid_start" => $raidStart,
					 "revert" => $i++ == 0 ? true : false); // only last entry allows reverting to prevent invalid unsuiciding for now
}

$smarty->assign("items", $items);
	
?>