<?php

header("Content-Type: application/xml");

$item = $_GET['item'];

print file_get_contents("http://de.wowhead.com/item=".$item."&xml");

?>