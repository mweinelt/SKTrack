<?php
/*
 * sktrack
 * Raid Selection Template Assistant
 *
 * Author: hexa-
 * File: tab_loot_distribution.php
 * Created: 26.03.2012, 06:31:03
 */

/* Content:
 * - Partial Ranking
 */
 
if (!isset($_SESSION['list_sel']))
	die("Ouch! No list defined to generate ranking for.");
 
/*******************
 * List Ranking
 *******************/

// get current positions on list
$query = "SELECT sk_list_position.*,sk_users.username as name
		  FROM sk_list_position
		  INNER JOIN sk_users ON sk_list_position.user_id = sk_users.id
		  WHERE list_id = ".$_SESSION['list_sel']."
		  ORDER BY position ASC";
$result = mysql_query($query);

$pool = array(); // list 
while ($row = mysql_fetch_assoc($result))
	$pool[] = array("pos" => $row['position'],
					"name" => $row['name'],
					"uid" => $row['user_id']);

$smarty->assign("pool", $pool);
 
?>