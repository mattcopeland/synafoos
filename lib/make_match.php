<?php
$time = time();
require_once('dbconnect.php');
$team_1_name = $_GET['t1name'];
$team_2_name = $_GET['t2name'];
$team_1_player_1 = $_GET['t1p1'];
$team_2_player_1 = $_GET['t2p1'];
$num_players = $_GET['num_players'];
$team_1_id = null;
$team_2_id = null;

// setup a 4 player series
if ($num_players == 4)
{
	// get the second players from each team
	$team_1_player_2 = $_POST['t1p2'];
	$team_2_player_2 = $_POST['t2p2'];
	// check to see if team 1 is already a defined team
	$query = "SELECT * FROM teams WHERE (player_1 = $team_1_player_1 OR player_2 = $team_1_player_1) AND (player_1 = $team_1_player_2 OR player_2 = $team_1_player_2)";
	$result = mysql_query($query);
	if ($result) {
		if (mysql_num_rows($result) == 1)
		{
			while($team_1 = mysql_fetch_assoc($result))
			{
				$team_1_id = $team_1["team_id"];
				$team_1_name = $team_1["nickname"];
			}
		}
	}
	// check to see if team 2 is already a defined team
	$query = "SELECT * FROM teams WHERE (player_1 = $team_2_player_1 OR player_2 = $team_2_player_1) AND (player_1 = $team_2_player_2 OR player_2 = $team_2_player_2)";
	$result = mysql_query($query);
	if ($result) {
		if (mysql_num_rows($result) == 1)
		{
			while($team_2 = mysql_fetch_assoc($result))
			{
				$team_2_id = $team_2["team_id"];
				$team_2_name = $team_2["nickname"];
			}
		}
	}
	// if team 1 and team 2 are already defined teams then check to see if they already have an active matchup
	if ($team_1_id && $team_2_id)
	{
		$query = "SELECT series_id FROM series WHERE players = $num_players AND (team_1 = $team_1_id OR team_2 = $team_1_id) AND (team_1 = $team_2_id OR team_2 = $team_2_id) AND active = 1";	
		$result = mysql_query($query);
		if ($result) {
			// if this is already an active series then don't create a new series
			if (mysql_num_rows($result) > 0)
			{
				$matchup = "old";
			}
			// create a new series
			else
			{
				$query = "INSERT INTO series (series_id, players, team_1, team_2, wins_1, wins_2, wins_goal, active, password, last_updated) VALUES ('', $num_players, $team_1_id, $team_2_id, 0, 0 , 50, 1, '', $time)";
				$result = mysql_query($query);
				$matchup = "new with defined teams";
			}
		}
	}
	// either team 1 or team 2 or both are new teams
	else
	{
		// if team 1 is a new team create the team
		if (!$team_1_id)
		{
			$query = "INSERT INTO teams (team_id, nickname, player_1, player_2) VALUES ('', '$team_1_name', $team_1_player_1, $team_1_player_2)";
			$result = mysql_query($query);
			$query = "SELECT * FROM teams ORDER BY team_id DESC LIMIT 1";
			$result = mysql_query($query);
			if ($result) {
				while($team_1 = mysql_fetch_assoc($result))
				{
					$team_1_id = $team_1["team_id"];
				}
			}
		}
		// if team 2 is a new team create the team
		if (!$team_2_id)
		{
			$query = "INSERT INTO teams (team_id, nickname, player_1, player_2) VALUES ('', '$team_2_name', $team_2_player_1, $team_2_player_2)";
			$result = mysql_query($query);
			$query = "SELECT * FROM teams ORDER BY team_id DESC LIMIT 1";
			$result = mysql_query($query);
			if ($result) {
				while($team_2 = mysql_fetch_assoc($result))
				{
					$team_2_id = $team_2["team_id"];
				}
			}
		}
		// create a new series with these two teams
		$query = "INSERT INTO series (series_id, players, team_1, team_2, wins_1, wins_2, wins_goal, active, password, last_updated) VALUES ('', $num_players, $team_1_id, $team_2_id, 0, 0 , 50, 1, '', $time)";
		$result = mysql_query($query);
		$matchup = "new with undefined teams";
	}
}
// set up a 2 player series
else
{
	// check to see if this is alreay an active series
	$query = "SELECT series_id FROM series WHERE players = $num_players AND (team_1 = $team_1_player_1 OR team_2 = $team_1_player_1) AND (team_1 = $team_2_player_1 OR team_2 = $team_2_player_1) AND active = 1";
	$result = mysql_query($query);
	if ($result) {
		if (mysql_num_rows($result) > 0)
		{
			$matchup = "old";
		}
		else
		{
			$query = "INSERT INTO series (series_id, players, team_1, team_2, wins_1, wins_2, wins_goal, active, password, last_updated) VALUES ('', $num_players, $team_1_player_1, $team_2_player_1, 0, 0 , 50, 1, '', $time)";
			$result = mysql_query($query);
			$matchup = "new singles";
		}
	}
}
echo $matchup;
mysql_close($link);
?>