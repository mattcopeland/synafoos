<?php
/*
 * Increase winning teams total by 1
 *
 */
require_once('dbconnect.php');
$series_id = $_POST['series_id'];
$winning_team = $_POST['winning_team'];
$losing_team = ($winning_team == 1 ? 2 : 1);
$action = $_POST['action'];
if ($action == 'plus') {
	$query = "UPDATE series SET wins_$winning_team = wins_$winning_team + 1 WHERE series_id = $series_id";
}
else if ($action == 'minus') {
	$query = "UPDATE series SET wins_$winning_team = wins_$winning_team - 1 WHERE series_id = $series_id";
}
$result = mysql_query($query);

// Make a series inactive
$query = "SELECT wins_$winning_team,wins_goal FROM series WHERE series_id = $series_id";
$result = mysql_query($query);
if ($result) {
	$series = mysql_fetch_array($result);
	if ($series['wins_'.$winning_team] == $series['wins_goal'])
	{
		$query = "UPDATE series SET active = 0 WHERE series_id = $series_id";
		$result = mysql_query($query);
	}
}

// Update the series log
if ($action == 'plus')
{
	$query = "SELECT players FROM series WHERE series_id = $series_id";
	$result = mysql_query($query);
	if ($result) {
		$series_players = mysql_fetch_assoc($result);
	}
	
	$query = "SELECT team_$winning_team FROM series WHERE series_id = $series_id";
	$result = mysql_query($query);
	if ($result) {
		$winner = mysql_fetch_assoc($result);
		$winner_id = $winner['team_'.$winning_team];
	}
	$query = "SELECT team_$losing_team FROM series WHERE series_id = $series_id";
	$result = mysql_query($query);
	if ($result) {
		$loser = mysql_fetch_assoc($result);
		$loser_id = $loser['team_'.$losing_team];
	}
	date_default_timezone_set("America/New_York");
	$time = time();
	$query = "INSERT INTO series_log (log_id, series_id, winner_id, loser_id, date_time) VALUES ('', $series_id, $winner_id, $loser_id, $time)";
	$result = mysql_query($query);
	
	// Get the last entry in the log
	$query = "SELECT * FROM series_log ORDER BY log_id DESC LIMIT 1";
	$result = mysql_query($query);
	$log = mysql_fetch_assoc($result);
	
	$log['date'] = date('n/j/Y',$log['date_time']);
	$log['time'] = date('g:i a',$log['date_time']);
	
	// Doubles Match
	if ($series_players['players'] == 4)
	{
		$query = "SELECT nickname FROM teams WHERE team_id = $log[winner_id]";
		$result = mysql_query($query);
		$winner = mysql_fetch_assoc($result);
		$log['winner'] = $winner['nickname'];
		
		$query = "SELECT nickname FROM teams WHERE team_id = $log[loser_id]";
		$result = mysql_query($query);
		$loser = mysql_fetch_assoc($result);
		$log['loser'] = $loser['nickname'];
	}
	// Singles Match
	else
	{
		$query = "SELECT nickname FROM players WHERE player_id = $log[winner_id]";
		$result = mysql_query($query);
		$winner = mysql_fetch_assoc($result);
		$log['winner'] = $winner['nickname'];
		
		$query = "SELECT nickname FROM players WHERE player_id = $log[loser_id]";
		$result = mysql_query($query);
		$loser = mysql_fetch_assoc($result);
		$log['loser'] = $loser['nickname'];
	}
}
// Rescind the victory
else
{
	$query = "SELECT team_$winning_team FROM series WHERE series_id = $series_id";
	$result = mysql_query($query);
	if ($result) {
		$winner = mysql_fetch_assoc($result);
		$winner_id = $winner['team_'.$winning_team];
	}
	$query = "SELECT log_id FROM series_log WHERE series_id = $series_id AND rescinded != 1 AND winner_id = $winner_id ORDER BY log_id DESC LIMIT 1";
	$result = mysql_query($query);
	$log_id = mysql_fetch_assoc($result);
	$query = "UPDATE series_log SET rescinded = 1 WHERE log_id = $log_id[log_id]";
	$result = mysql_query($query);
	$log['log_id'] = $log_id[log_id];
}

// Update the winners score 
$query = "SELECT * FROM series WHERE series_id = $series_id";
$result = mysql_query($query);
$wins = mysql_fetch_assoc($result);
$log['wins'] = $wins['wins_'.$winning_team];
$result = json_encode($log, JSON_NUMERIC_CHECK);
echo $result;
mysql_close($link);
?>
