<?php

/*
 * sktrack
 * Request Handler
 *
 * Author: hexa-
 * File: handler.inc.php
 * Created: 26.03.2012, 06:31:03
 */

// process authentication request
if (isset ($_POST['do']))
{
	
	$do = $_POST['do'];
	
	if ($do == "login")
	{
		$salt= md5(strtoupper($_POST['username']) . ":" . $_POST['password']);
		//echo $salt;
		$query= "SELECT id,username FROM sk_users WHERE username = '" . strtolower(mysql_real_escape_string($_POST['username'])) . "' AND password = '" . $salt . "'";
		$result= mysql_query($query);

		if (mysql_num_rows($result) > 0)
		{
			$row= mysql_fetch_assoc($result);
			$_SESSION['auth']= true;
			$_SESSION['auth_id'] = $row['id'];
			$_SESSION['auth_name']= $row['username'];
		}
	}
	// process logout
	elseif ($do == "logout")
	{
		// remember list selection
		$list = $_SESSION['list_sel'];
		
		// delete session data
		$_SESSION= array ();
		session_destroy();
		
		// set remembered list sel
		$_SESSION['list_sel'] = $list;
	}
	// list add handler
	elseif ($do == "addlist")
	{
		if (!empty ($_POST['title']))
			mysql_query("INSERT INTO sk_lists (title) VALUES ('" . mysql_real_escape_string($_POST['title']) . "')");
	}
	elseif ($do == "startraid")
	{		
		$players = $_POST['raid'];
		$list = intval($_POST['list']);
		$title = mysql_real_escape_string($_POST['raidname']);

		// create the raid
		$query = "INSERT INTO sk_raids (list_id, title) VALUES
				  ($list, '".$title."')";
		mysql_query($query);

		$_SESSION['raid_sel'] = mysql_insert_id();
		$raid_id = $_SESSION['raid_sel'];
				
		if (count($players) > 0)
		{
			$player_ids = array();
			foreach ($players as $p)
				$player_ids[] = $p;
			
			// set correct user ids to active	
			$query = "UPDATE sk_list_position SET raid_id = $raid_id WHERE list_id = $list AND user_id IN (".implode(",", $player_ids).")";
			mysql_query($query);
			
			$query = "UPDATE sk_list_position SET raid_id = -1 WHERE list_id = $list AND  user_id NOT IN (".implode(",", $player_ids).")";
			mysql_query($query);
			
			// set loot distribution to active
			$query = "UPDATE sk_lists SET active_raid = $raid_id WHERE id = ".$list."";
			mysql_query($query);			
		}
		
	}
	elseif ($do == "endraid")
	{
		$list = intval($_POST['list']);
		$raid_id = $_SESSION['raid_sel'];
		
		// remove players from raid
		$query = "UPDATE sk_list_position SET raid_id = -1 WHERE list_id = ".$list."";
		mysql_query($query);		
		
		// set loot distribution to inactive
		$query = "UPDATE sk_lists SET active_raid = -1 WHERE id = ".$list."";
		mysql_query($query);			
		
		// set raid endtime
		$query = "UPDATE sk_raids SET end = NOW() WHERE list_id = $list AND id = $raid_id";
		mysql_query($query);
		
		// if no items were looted remove the raid
		$query = "SELECT item_id FROM sk_item_log WHERE raid_id = $raid_id";
		$result = mysql_query($query);
		
		if (mysql_num_rows($result) == 0)
		{
			$query = "DELETE FROM sk_raids WHERE id = $raid_id";
			mysql_query($query);
		}
		
	}
	elseif ($do == "distribute")
	{
		assert(is_numeric($_POST['player'])); // possible injection or malformed post request

		$player = intval($_POST['player']);
		$item = $_POST['itemvalid'] != -1 ? $_POST['item'] : NULL;
		$lootmode = intval($_POST['lootmode']);

		assert($item != null); // malformed post data, possibly manual form activation (should happen automatically through js) or template error
		
		$list_id = $_SESSION['list_sel'];
		$raid_id = $_SESSION['raid_sel'];

		assert(is_numeric($list_id));
		assert(is_numeric($raid_id));
		
		// position updates only relevant when suicide loot mode
		$pos_old = -1;
		$pos_new = -1;
		
		if (is_numeric($player) && is_numeric($item) && is_numeric($lootmode)) // TODO: remove redundant conditions
		{
			
			if ($lootmode == 0)
			// Suicide
			{
				// get all active positions
				$query = "SELECT * FROM sk_list_position WHERE list_id = $list_id AND raid_id = $raid_id ORDER BY position ASC";
				$result = mysql_query($query);
								
				$pos = array(); // Raid Positions
				$old_order = array(); // Raid Players
				
				// write back old order...
				while ($row = mysql_fetch_array($result))
				{
					if ($row['user_id'] == $player)
						$pos_old = $row['position'];
					
					$pos[] = $row['position']; // selected positions come in ascending order
					$old_order[] = $row['user_id'];
				}
				$pos_new = $pos[count($pos)-1]; // count($pos) == amount of players in raid, we count from 0, so last spot is count($pos)-1
				
				// ...and write new order WITHOUT the userid of the player who suicided...
				$new_order = array();
				foreach ($old_order as $uid)
					if ($uid != $player) 
						$new_order[] = $uid;
				
				// ...and append it to the new order AFTER everyone else
				$new_order[] = $player;	

				// SAFEGUARD: We should not be losing any players in this process!
				assert(count($new_order) == count($old_order));
				
				// remove old entrys, because unique key swapping is rather complicated in sql
				$query = "DELETE FROM sk_list_position WHERE list_id = $list_id and user_id IN (".implode(",", $old_order).")";
				mysql_query($query);
				
				for ($i = 0; $i < count($old_order); $i++)
				{
					// combine new positions with a userid, they should be at the same position in the arrays
					$new_pos = $pos[$i];
					$uid = $new_order[$i];
					
					$query = "INSERT INTO sk_list_position (list_id, user_id, position, raid_id) VALUES
						  ($list_id, $uid, $new_pos, $raid_id)";
					mysql_query($query);
				}	
				
			}
			
			// finally, if everything went well, document the item in the log (item, old pos, new pos, need/greed/disenchant, sk moderator)
			$query = "INSERT INTO sk_item_log (user_id, item_id, list_id, raid_id, pos_old, pos_new, lootmode, signee) VALUES
				  ($player, $item, $list_id, $raid_id, $pos_old, $pos_new, $lootmode, ".$_SESSION['auth_id'].")";
			mysql_query($query);	
		}
	}
	elseif ($do == "revert")
	{
		$list_id = $_SESSION['list_sel'];
		$raid_id = $_SESSION['raid_sel'];

		assert(is_numeric($list_id)); // checks for corrupt session data
		assert(is_numeric($raid_id));
		
		// get last itemlog entry for the specified raid
		$query = "SELECT * FROM sk_item_log WHERE raid_id = $raid_id ORDER BY date DESC LIMIT 1";
		$result = mysql_query($query);
		
		$row = mysql_fetch_assoc($result);
		$logentry = $row['entry'];
		
		if ($row['lootmode'] == 0) // Lootmode == Suicide?
		{
			// revert suicide ;_;
			$old_pos = $row['pos_old'];
			$new_pos = $row['pos_new'];
			
			assert($old_pos != -1 && $new_pos != -1); // invalid positions (from db, so if an assert pops here, error should be in do=distribute)
			
			// get current order
			$query = "SELECT * FROM sk_list_position WHERE list_id = $list_id AND raid_id = $raid_id ORDER BY position ASC";
			$result = mysql_query($query);
	
			// write back current list order
			$pos = array();
			$old_order = array();
			while ($row = mysql_fetch_array($result))
			{
				$pos[] = $row['position'];
				$old_order[] = $row['user_id'];
			}
						
			// get suicided uid and put it back up to $old_pos
			$uid = $old_order[count($old_order)-1]; // suicide uid is always at the bottom of the list
			
			$new_order = array();
			for ($i = 0; $i < count($old_order)-1; $i++) // NOTE: loop count(raid_members)-1 times, because we auto-insert $uid at correct $pos[$i] anyway, which
								     //       results in $n+1 inserts into the array which is count(raid_members).
								     //       We can safely skip the last element because it is always the suicide user.
			{
				if ($pos[$i] == $old_pos)
					$new_order[] = $uid;
				
				$new_order[] = $old_order[$i];
			}

			// remove old entrys, because unique key swapping is rather complicated in sql
			$query = "DELETE FROM sk_list_position WHERE list_id = $list_id and user_id IN (".implode(",", $old_order).")";
			mysql_query($query);
			
			// write back new order
			for ($i = 0; $i < count($old_order); $i++)
			{
				// combine uid with new position
				$new_pos = $pos[$i];
				$uid = $new_order[$i];

				$query = "INSERT INTO sk_list_position (list_id, user_id, position, raid_id) VALUES
					  ($list_id, $uid, $new_pos, $raid_id)";
				mysql_query($query);
			}
		}
		
		// remove item from log
		$query = "DELETE FROM sk_item_log WHERE entry = $logentry";
		mysql_query($query);
		
	}
	elseif ($do == "adduser")
	{
		// post data
		$name = mysql_real_escape_string($_POST['name']);
		$list = intval($_POST['list']);
		
		// check if player exists in system
		$query = "SELECT * FROM sk_users WHERE UPPER(username) = '".strtoupper($name)."'";
		$result = mysql_query($query);
		
		$uid = -1;
		// if not, create
		if (!(mysql_num_rows($result) > 0))
		{
			$query = "INSERT INTO sk_users (username) VALUES ('".$name."')";
			$result = mysql_query($query);
			$uid = mysql_insert_id();		
		}
		// else get sk_users.id
		else
		{
			$row = mysql_fetch_assoc($result);
			$uid = $row['id'];
		}
				
		// check for existing entry in the list
		$query = "SELECT * FROM sk_list_position WHERE list_id = ".$list." AND user_id = ".$uid."";
		$result = mysql_query($query);
		
		print_r(mysql_fetch_assoc($result));
		
		if (!(mysql_num_rows($result) > 0))
		{
			// get max pos for list
			$query = "SELECT MAX(position) as max FROM sk_list_position WHERE list_id = ".$list."";
			$result = mysql_query($query);
			
			$row = mysql_fetch_assoc($result);
			if ($row['max'] == NULL)
				$max = 0;
			else
				$max = ++$row['max'];
						
			// else add to list
			$query = "INSERT INTO sk_list_position (list_id, user_id, position) VALUES
					 (".$list.", ".$uid.", ".$max.")";
			$result = mysql_query($query);
		}
				
	}
}
?>
