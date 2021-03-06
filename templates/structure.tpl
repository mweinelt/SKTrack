<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
 	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="style.css" />
		<script type="text/javascript" src="sk.js"></script>
		<!--<script type="text/javascript" src="http://static.wowhead.com/widgets/power.js"></script>-->
		<script type="text/javascript" src="http://cdn.openwow.com/api/tooltip.js"></script>
	</head>
	<body>
		<div id="session">
		{if $logged_in}
			<form id="logout" method="post" action="index.php">
				<input type="hidden" name="do" value="logout" />
				Angemeldet als {$auth_name}. <input style="border: 0px;" type="submit" value="&#215;">
			</form>
		{else}
			<form id="login" method="post" action="index.php"> 
				<input type="hidden" name="do" value="login" />
				<input class="text" type="text" name="username" />
				<input class="text" type="password" name="password" />
				<input style="border: 0px;" type="submit" value="&#187;" />
			</form>
		{/if}
		</div>
		{include file="tabs.tpl"}
	</body>
</html>
