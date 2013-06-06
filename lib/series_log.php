<?php
/*
 * Get the logs of a particular series
 */
date_default_timezone_set('UTC');
require_once('dbconnect.php');
$series_id = $_POST['series_id'] ? $_POST['series_id'] : 1;
$num_players = $_POST['num_players'] ? $_POST['num_players'] : 2;
$action = $_POST['action'] ? $_POST['action'] : "log";
//$series_id = 8;
//$action = "details";
$series_log_arr = array();
$i = 0;

// Doubles Match
if ($num_players == 4)
{
	$table = "teams";
	$field = "team_id";
}
else
{
	$table = "players";
	$field = "player_id";
}
// Get info details about this series
if ($action == "details")
{
	$query = "SELECT players,team_1,team_2,wins_1,wins_2,active FROM series WHERE series_id = $series_id LIMIT 1";
	$result = mysql_query($query);
	if ($result) {
		$series_details = mysql_fetch_assoc($result);
		// Doubles Match
		if ($series_details["players"] == 4)
		{
			// Get the team names
			$query = "SELECT nickname FROM $table WHERE $field = $series_details[team_1]";
			$result2 = mysql_query($query);
			if ($result2) {
				$team_1 = mysql_fetch_assoc($result2);
				$series_details["team_1_details"]["nickname"] = $team_1["nickname"];
			}
			$query = "SELECT nickname FROM $table WHERE $field = $series_details[team_2]";
			$result2 = mysql_query($query);
			if ($result2) {
				$team_2 = mysql_fetch_assoc($result2);
				$series_details["team_2_details"]["nickname"] = $team_2["nickname"];
			}
			// Get the nicknames and images for the players on team 1
			$query = "SELECT player_1,player_2 FROM $table WHERE $field = $series_details[team_1]";
			$result2 = mysql_query($query);
			if ($result2) {
				$team_1_players = mysql_fetch_assoc($result2);
				$query = "SELECT nickname,image FROM players WHERE player_id = $team_1_players[player_1]";
				$result3 = mysql_query($query);
				if ($result3) {
					$team_1_player_1 = mysql_fetch_assoc($result3);
					$series_details["team_1_details"]["player_1"] = $team_1_player_1;
				}
				$query = "SELECT nickname,image FROM players WHERE player_id = $team_1_players[player_2]";
				$result3 = mysql_query($query);
				if ($result3) {
					$team_1_player_2 = mysql_fetch_assoc($result3);
					$series_details["team_1_details"]["player_2"] = $team_1_player_2;
				}
			}
			// Get the nicknames and images for the players on team 2
			$query = "SELECT player_1,player_2 FROM $table WHERE $field = $series_details[team_2]";
			$result2 = mysql_query($query);
			if ($result2) {
				$team_2_players = mysql_fetch_assoc($result2);
				$query = "SELECT nickname,image FROM players WHERE player_id = $team_2_players[player_1]";
				$result3 = mysql_query($query);
				if ($result3) {
					$team_2_player_1 = mysql_fetch_assoc($result3);
					$series_details["team_2_details"]["player_1"] = $team_2_player_1;
				}
				$query = "SELECT nickname,image FROM players WHERE player_id = $team_2_players[player_2]";
				$result3 = mysql_query($query);
				if ($result3) {
					$team_2_player_2 = mysql_fetch_assoc($result3);
					$series_details["team_2_details"]["player_2"] = $team_2_player_2;
				}
			}
		}
		// Singles Match
		else
		{
			// Get the team names
			$query = "SELECT nickname,image FROM $table WHERE $field = $series_details[team_1]";
			$result2 = mysql_query($query);
			if ($result2) {
				$team_1 = mysql_fetch_assoc($result2);
				$series_details["team_1_details"]["nickname"] = $team_1["nickname"];
				$series_details["team_1_details"]["player_1"]["nickname"] = $team_1["nickname"];
				$series_details["team_1_details"]["player_1"]["image"] = $team_1["image"];
			}
			$query = "SELECT nickname ,image FROM $table WHERE $field = $series_details[team_2]";
			$result2 = mysql_query($query);
			if ($result2) {
				$team_2 = mysql_fetch_assoc($result2);
				$series_details["team_2_details"]["nickname"] = $team_2["nickname"];
				$series_details["team_1_details"]["player_2"]["nickname"] = $team_2["nickname"];
				$series_details["team_2_details"]["player_1"]["image"] = $team_2["image"];
			}
		}
	}
	$result = json_encode($series_details, JSON_NUMERIC_CHECK);
	echo $result;
	mysql_close($link);
}

if ($action == "log")
{
	// Get the log for this series
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
			// Add some things to the series log
			$series_log["game_date"] = date("n/j/Y",$series_log["date_time"]);
			$series_log["winner"] = $winner["nickname"];
			$series_log["loser"] = $loser["nickname"];

			$series_log_arr[$i] = array(
				"series_log" => $series_log,
			);
			++$i;
		}
	}
	$result = json_encode($series_log_arr, JSON_NUMERIC_CHECK);
	echo $result;
	mysql_close($link);
}
?>