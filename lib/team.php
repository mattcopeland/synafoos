<?php
/*
 * Get a team
 */
require_once('dbconnect.php');
$player_1 = $_GET['player_1'];
$player_2 = $_GET['player_2'];
$query = "SELECT * FROM teams WHERE (player_1 = $player_1 OR player_2 = $player_1) AND (player_1 = $player_2 OR player_2 = $player_2)";
$result = mysql_query($query);
if ($result)
{
	$team = mysql_fetch_assoc($result);
}
mysql_close($link);
$result = json_encode($team, JSON_NUMERIC_CHECK);
echo $result;
?>