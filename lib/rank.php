<?php
/*
 * Rank the players
 */
require_once('dbconnect.php');
$rank = 1;
$query = "SELECT player_id FROM players";
$result = mysql_query($query);
if ($result)
{
	while($player = mysql_fetch_assoc($result))
	{
		// Reset tptals for this player
		$player['wins'] = 0;
		$player['loses'] = 0;
		$player['wins_dbl'] = 0;
		$player['loses_dbl'] = 0;
		// Get individual wins
		$query = "SELECT wins_1,wins_2 FROM series WHERE team_1 = $player[player_id] AND players = 2";
		$result2 = mysql_query($query);
		if($result2)
		{
			while($wins = mysql_fetch_assoc($result2))
			{
				$player['wins'] = $player['wins'] + $wins['wins_1'];
				$player['loses'] = $player['loses'] + $wins['wins_2'];
			}
		}
		$query = "SELECT wins_1,wins_2 FROM series WHERE team_2 = $player[player_id] AND players = 2";
		$result2 = mysql_query($query);
		if($result2)
		{
			while($wins = mysql_fetch_assoc($result2))
			{
				$player['wins'] = $player['wins'] + $wins['wins_2'];
				$player['loses'] = $player['loses'] + $wins['wins_1'];
			}

		}
		// Get the teams this player is a part of
		$query = "SELECT team_id FROM teams WHERE player_1 = $player[player_id] OR player_2 = $player[player_id]";
		$result2 = mysql_query($query);
		if($result2)
		{
			while($teams = mysql_fetch_assoc($result2))
			{
				// Wins where this player is on team 1
				$query = "SELECT wins_1,wins_2 FROM series WHERE team_1 = $teams[team_id] AND players = 4";
				$result3 = mysql_query($query);
				if($result3)
				{
					while($wins = mysql_fetch_assoc($result3))
					{
						$player['wins_dbl'] = $player['wins_dbl'] + $wins['wins_1'];
						$player['loses_dbl'] = $player['loses_dbl'] + $wins['wins_2'];
					}
				}
				// Wins where this player is on team 2
				$query = "SELECT wins_1,wins_2 FROM series WHERE team_2 = $teams[team_id] AND players = 4";
				$result3 = mysql_query($query);
				if($result3)
				{
					while($wins = mysql_fetch_assoc($result3))
					{
						$player['wins_dbl'] = $player['wins_dbl'] + $wins['wins_2'];
						$player['loses_dbl'] = $player['loses_dbl'] + $wins['wins_1'];
					}
				}
			}
		}
		$games_played = $player['wins'] + $player['loses'] + $player['wins_dbl'] + $player['loses_dbl'] + 1;
		$player['points'] = round((((($player['wins']) * .25 - ($player['loses'] * .15)) + (($player['wins_dbl'] * .15) - ($player['loses_dbl'] * .05)) + ($games_played * .5)) / ($games_played / ($games_played * .75))),4);
		
		$query = "REPLACE INTO rankings (rank, player_id, points) VALUES ($rank, $player[player_id], $player[points])";
		$result4 = mysql_query($query);
		$rank++;
	}
}
// Reoder the rankings based on points
$rank = 1;
$query = "SELECT * FROM rankings ORDER BY points DESC";
$result5 = mysql_query($query);
if($result5)
{
	while($rankings = mysql_fetch_assoc($result5))
	{
		$query = "REPLACE INTO rankings (rank, player_id, points) VALUES ($rank, $rankings[player_id], $rankings[points])";
		$result6 = mysql_query($query);
		$rank++;
	}
}
mysql_close($link);