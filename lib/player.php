<?php
/*
 * Get a player
 */
require_once('dbconnect.php');
$player_id = $_GET['player_id'] ? $_GET['player_id'] : 1;
$type = $_GET['type'] ? $_GET['type'] : 'HTML';
$query = "SELECT * FROM players WHERE player_id = $player_id";
$result = mysql_query($query);
if ($result)
{
	$player = mysql_fetch_assoc($result);
	$player['wins'] = 0;
	$player['loses'] = 0;
	$player['wins_dbl'] = 0;
	$player['loses_dbl'] = 0;
	// Get the teams this player is a part of
	$query = "SELECT team_id FROM teams WHERE player_1 = $player_id OR player_2 = $player_id";
	$result = mysql_query($query);
	if($result)
	{
		while($teams = mysql_fetch_assoc($result))
		{
			// Wins where this player is on team 1
			$query = "SELECT wins_1,wins_2 FROM series WHERE team_1 = $teams[team_id] AND players = 4";
			$result2 = mysql_query($query);
			if($result2)
			{
				while($wins = mysql_fetch_assoc($result2))
				{
					$player['wins_dbl'] = $player['wins_dbl'] + $wins['wins_1'];
					$player['loses_dbl'] = $player['loses_dbl'] + $wins['wins_2'];
				}
			}
			// Wins where this player is on team 2
			$query = "SELECT wins_1,wins_2 FROM series WHERE team_2 = $teams[team_id] AND players = 4";
			$result2 = mysql_query($query);
			if($result2)
			{
				while($wins = mysql_fetch_assoc($result2))
				{
					$player['wins_dbl'] = $player['wins_dbl'] + $wins['wins_2'];
					$player['loses_dbl'] = $player['loses_dbl'] + $wins['wins_1'];
				}
			}
		}
	}
	// Get individual wins
	$query = "SELECT wins_1,wins_2 FROM series WHERE team_1 = $player_id AND players = 2";
	$result = mysql_query($query);
	if($result)
	{
		while($wins = mysql_fetch_assoc($result))
		{
			$player['wins'] = $player['wins'] + $wins['wins_1'];
			$player['loses'] = $player['loses'] + $wins['wins_2'];
		}
	}
	$query = "SELECT wins_1,wins_2 FROM series WHERE team_2 = $player_id AND players = 2";
	$result = mysql_query($query);
	if($result)
	{
		while($wins = mysql_fetch_assoc($result))
		{
			$player['wins'] = $player['wins'] + $wins['wins_2'];
			$player['loses'] = $player['loses'] + $wins['wins_1'];
		}

	}
}
$query = "SELECT rank,points FROM rankings WHERE player_id = $player_id";
$result = mysql_query($query);
if($result)
{
	$ranking = mysql_fetch_assoc($result);
	$player['rank'] = $ranking['rank'];
}
mysql_close($link);

// Return JSON encoded data
if ($type == 'JSON') {
	$result = json_encode($player, JSON_NUMERIC_CHECK);
	echo $result;
}
//Retrun HTML data
else {
//HTML Payload
echo '
<section class="player">
	<img class="player_img" src="img/players/' . $player['image'] . '.jpg" alt="' . $player['nickname'] . '" />
	<div class="player_info">
		<div class="nickname">' . $player['nickname'] . '</div>
		<div class="playername">a.k.a. ' . $player['first_name'] . ' ' . $player['last_name'] . '</div>
	</div>
	<div class="wins_singles">
		<h4>Singles</h4>
		<div class="wins"><label>Wins:</label>' . $player['wins'] . '</div>
		<div class="loses"><label>Loses:</label>' . $player['loses'] . '</div>
	</div>
	<div class="wins_doubles">
		<h4>Doubles</h4>
		<div class="wins"><label>Wins:</label>' . $player['wins_dbl'] . '</div>
		<div class="loses"><label>Loses:</label>' . $player['loses_dbl'] . '</div>
	</div>
	<div class="rank">
		<h4>Rank</h4>
		<div>' . $player['rank'] . '</div>
	</div>
</section>';
}
?>