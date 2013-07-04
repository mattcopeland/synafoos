<?php
/*
 * Get the logs of a particular series
 */
date_default_timezone_set('UTC');
require_once('dbconnect.php');
$series_id = $_GET['series_id'] ? $_GET['series_id'] : 1;
$num_players = $_GET['num_players'] ? $_GET['num_players'] : 2;
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
		$query = "SELECT nickname,image FROM $table WHERE $field = $series_details[team_2]";
		$result2 = mysql_query($query);
		if ($result2) {
			$team_2 = mysql_fetch_assoc($result2);
			$series_details["team_2_details"]["nickname"] = $team_2["nickname"];
			$series_details["team_2_details"]["player_1"]["nickname"] = $team_2["nickname"];
			$series_details["team_2_details"]["player_1"]["image"] = $team_2["image"];
		}
	}
}

$html_payload = '<section series_id="' . $series_id . '" class="series_details';
if ($series_details["players"] == 4) {
	$html_payload .= ' double';
}
else {
	$html_payload .= ' single';
}

$html_payload .= '"><div class="team"><h2>' . $series_details["team_1_details"]["nickname"] . '</h2><div class="players"><img class="player_img" src="img/players/' . $series_details["team_1_details"]["player_1"]["image"] . '.jpg" alt="' . $series_details["team_1_details"]["player_1"]["nickname"] . '" />';

if ($series_details["players"] == 4) {
	$html_payload .= '<img class="player_img" src="img/players/' . $series_details["team_1_details"]["player_2"]["image"] . '.jpg" alt="' . $series_details["team_1_details"]["player_2"]["nickname"] . '" />';
}

$html_payload .= '</div><div class="wins"><span>wins</span><em>' . $series_details["wins_1"] . '</em>';

if ($series_details["active"] == 1) {
	$html_payload .= '<div class="controls"><b class="plus" team_num="1">+</b></div>';
}

$html_payload .= '</div></div><div class="team"><h2>' . $series_details["team_2_details"]["nickname"] . '</h2><div class="players"><img class="player_img" src="img/players/' . $series_details["team_2_details"]["player_1"]["image"] . '.jpg" alt="' . $series_details["team_2_details"]["player_1"]["nickname"] . '" />';

if ($series_details["players"] == 4) {
	$html_payload .= '<img class="player_img" src="img/players/' . $series_details["team_2_details"]["player_2"]["image"] . '.jpg" alt="' . $series_details["team_2_details"]["player_2"]["nickname"] . '" />';
}

$html_payload .= '</div><div class="wins"><span>wins</span><em>' . $series_details["wins_2"] . '</em>';

if ($series_details["active"] == 1) {
	$html_payload .= '<div class="controls"><b class="plus" team_num="2">+</b></div>';
}

$html_payload .= '</div></div></section>';


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

$html_payload .= '<section class="series_log series_log_modal"><div class="series_log_head"><div class="winner">Winner</div><div class="date">Date</div></div><div class="series_log_data">';

foreach ($series_log_arr as $series) {
	$rescinded = "";
	if ($series["series_log"]["rescinded"] == 1) {
		$rescinded = " rescinded";
	}
	$html_payload .= '<div class="series_log_match' . $rescinded . '" log_id="' . $series["series_log"]["log_id"] . '"><div class="winner">' . $series["series_log"]["winner"] . '</div><div class="date">' . $series["series_log"]["game_date"] . '</div></div>';
}

$html_payload .= '</div></section>';

echo $html_payload;
mysql_close($link);
?>