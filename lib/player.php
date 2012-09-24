<?php
/*
 * Get the players
 */
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
?>