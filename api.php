<?php
require("lib/session.inc.php");

if(isset($_GET['rl']))
    $rl = (int) $_GET['rl'];
else
	$rl = 0;

$query = 'SELECT `sk_list_position`.`position` AS `position` , `sk_users`.`username` AS `name`
                FROM sk_list_position
                INNER JOIN sk_users ON sk_list_position.user_id = sk_users.id
                WHERE list_id = "'.$rl.'"
                ORDER BY position ASC';
$result = mysql_query($query);

header("content-type: text/xml");

echo '<?xml version="1.0" encoding="utf-8" ?>';
echo '<list id="'.$rl.'" timestamp="'.time().'" >';
while ($row = mysql_fetch_assoc($result))
{
    echo '<char position="'.$row['position'].'" name="'.$row['name'].'" />';
}
echo '</list>';
?>