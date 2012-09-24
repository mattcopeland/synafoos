<?php
/*
 * Get the players
 */
$players_arr = array();
$i = 0;

$query = "SELECT * FROM players ORDER BY player_id";
$result = mysql_query($query);
if ($result) {
	while($players = mysql_fetch_assoc($result))
	{
		$players_arr[$i] = $players;
		++$i;
	}
}
?>