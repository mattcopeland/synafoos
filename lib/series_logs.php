<?php
/*
 * Get the logs of a 10 most recent series matches
 */
$series_log_arr = array();
$i = 0;

$query = "SELECT series.series_id,series.players,series.team_1,series.team_2,series_log.winner_id,series_log.loser_id,series_log.date_time FROM series LEFT JOIN series_log ON series.series_id = series_log.series_id WHERE series_log.rescinded != 1 ORDER BY series_log.log_id DESC LIMIT 10"; 
	 
$result = mysql_query($query);
if ($result) {
	while($series_log = mysql_fetch_assoc($result))
	{	
		// Doubles Match
		if ($series_log['players'] == 4)
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
		$query = "SELECT nickname FROM $table WHERE $field = $series_log[winner_id]";
		$result2 = mysql_query($query);
		if ($result2) {
			$winner = mysql_fetch_assoc($result2);
			$series_log["winner"] = $winner["nickname"];
		}
		$query = "SELECT nickname FROM $table WHERE $field = $series_log[loser_id]";
		$result2 = mysql_query($query);
		if ($result2) {
			$loser = mysql_fetch_assoc($result2);
			$series_log["loser"] = $loser["nickname"];
		}
		
		$series_log_arr[$i] = $series_log;
		++$i;
	}
}
?>