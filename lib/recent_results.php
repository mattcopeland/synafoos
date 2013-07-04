<?php
/*
 * Get the logs of a 10 most recent series matches
 */
date_default_timezone_set('America/New_York');
require_once('dbconnect.php');
$series_log_arr = array();
$i = 0;

$query = "SELECT series.series_id,series.players,series.team_1,series.team_2,series_log.winner_id,series_log.loser_id,series_log.date_time
		  FROM series
		  LEFT JOIN series_log
		  ON series.series_id = series_log.series_id
		  WHERE series_log.rescinded != 1
		  ORDER BY series_log.log_id
		  DESC LIMIT 10";
	 
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
// HTML Payload
echo '
<h2>Recent Results</h2>
<section class="series_log">
	<div class="series_log_head">
		<div class="winner">Winner</div>
		<div class="loser">Loser</div>
		<div class="date">Date</div>
	</div>
	<div class="series_log_data">';
		foreach ($series_log_arr as $sl)
		{
		echo '
		<div class="series_log_match'; echo '" series_id="' . $sl['series_id'] . '">
			<div class="winner">' . $sl['winner'] . '</div>
			<div class="loser">' . $sl['loser'] . '</div>
			<div class="date">';
			if (date('Ymd',$sl['date_time']) == date('Ymd'))
			{
				$game_date = "Today";
			}
			else if ((date('Ymd',$sl['date_time']) + 1) == date('Ymd'))
			{
				$game_date = "Yesterday";
			}
			else
			{
				$game_date = date('n/j/Y',$sl['date_time']);
			}
			echo $game_date .'</div>
		</div>
		';
		}
	echo '
	</div>
</section>';
?>