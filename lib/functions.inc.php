<?php
/*
 * sktrack
 * Function Library
 *
 * Author: hexa-
 * File: functions.inc.php
 * Created: 26.03.2012, 06:31:03
 */

function timetostr($timestamp)
{
	$date = "";
	if (date("Y-m-d", $timestamp) == date("Y-m-d"))
		$date .= "Heute, ".strftime("%I:%M", $timestamp);
	elseif (date("Y-m-d", $timestamp) == date("Y-m-d", time() - 86400))
		$date .= "Gestern, ".strftime("%I:%M", $timestamp);
	else
		$date .= strftime("%d. %b %Y, %I:%M", $timestamp);

	return $date;

}

function lootmode($mode, $old = -1, $new = -1)
{
	switch ($mode)
	{
		case 0:
			$lootmode = "Bedarf (".($old + 1)." &#8658; ".($new + 1).")";
			break;
		case 1:
			$lootmode = "Gier";
			break;
		case 2:
			$lootmode = "Entzaubern";
			break;
		default:
			$lootmode = "ERROR";
			break;
	}
	
	return $lootmode;
	
}

function handleTZ($timestamp)
{
	global $tz;

	// timestamps in mysql can have a different timezone as system,
	// thanks to http://stackoverflow.com/questions/4573660/php-mysql-timestamp-and-timezones
	$time = new DateTime($timestamp, new DateTimeZone($tz['mysql']));
	$time->setTimezone(new DateTimeZone($tz['target']));

	return $time->getTimestamp();	
}

?>