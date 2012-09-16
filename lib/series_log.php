<?php
/*
 * Get the logs of a particular series
 */
$series_log_arr = array();
$i = 0;
// Doubles Match
if ($series_arr[0]['series']['players'] == 4)
{
	$table = "teams";
	$field = "team_id";
}
// Singles Match
else
{
	$table = "players";
	$field = "player_id";
}
$query = "SELECT * FROM series_log WHERE series_id = $series_id ORDER BY log_id DESC";
$result = mysql_query($query);
if ($result) {
	while($series_log = mysql_fetch_assoc($result))
	{	
		$query = "SELECT nickname FROM $table WHERE $field = $series_log[winner_id]";
		$result2 = mysql_query($query);
		if ($result2) {
			$winner = mysql_fetch_assoc($result2);
		}
		$query = "SELECT nickname FROM $table WHERE $field = $series_log[loser_id]";
		$result2 = mysql_query($query);
		if ($result2) {
			$loser = mysql_fetch_assoc($result2);
		}
		$series_log_arr[$i] = array(
			"series_log" => $series_log,
			"winner" => $winner["nickname"],
			"loser" => $loser["nickname"],
		);
		++$i;
	}
}
?>