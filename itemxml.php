<?php

header("Content-Type: application/xml");

$item = intval($_GET['item']); // sanitize input

if ($xml = file_get_contents("http://de.wowhead.com/item=".$item."&xml"))
	print $xml;
else 
	print "<error>file_get_contents(): error for item id ".$item."</error>";

?>