<?php
/*
 * Rank the players
 */
require_once('pdoconnect.php');
$rank = 1;
$stmt = $conn->prepare('SELECT player_id FROM players');
$stmt->execute();
while($player = $stmt->fetch(PDO::FETCH_ASSOC)) {
	// Reset tptals for this player
	$player['wins'] = 0;
	$player['losses'] = 0;
	$player['wins_dbl'] = 0;
	$player['losses_dbl'] = 0;
	// Get individual wins
	$stmt2 = $conn->prepare('SELECT wins_1,wins_2 FROM series WHERE team_1 = :player_id AND players = 2');
	$stmt2->execute(array('player_id' => $player['player_id']));
	while($wins = $stmt2->fetch(PDO::FETCH_ASSOC)) {
		$player['wins'] = $player['wins'] + $wins['wins_1'];
		$player['losses'] = $player['losses'] + $wins['wins_2'];
	}
	$stmt2 = $conn->prepare('SELECT wins_1,wins_2 FROM series WHERE team_2 = :player_id AND players = 2');
	$stmt2->execute(array('player_id' => $player['player_id']));
	while($wins = $stmt2->fetch(PDO::FETCH_ASSOC)) {
		$player['wins'] = $player['wins'] + $wins['wins_2'];
		$player['losses'] = $player['losses'] + $wins['wins_1'];
	}
	// Get the teams this player is a part of
	$stmt2 = $conn->prepare('SELECT team_id FROM teams WHERE player_1 = :player_id OR player_2 = :player_id');
	$stmt2->execute(array('player_id' => $player['player_id']));
	while($teams = $stmt2->fetch(PDO::FETCH_ASSOC)) {
		// Wins where this player is on team 1
		$stmt3 = $conn->prepare('SELECT wins_1,wins_2 FROM series WHERE team_1 = :team_id  AND players = 4');
		$stmt3->execute(array('team_id' => $teams['team_id']));
		while($wins = $stmt3->fetch(PDO::FETCH_ASSOC)) {
			$player['wins_dbl'] = $player['wins_dbl'] + $wins['wins_1'];
			$player['losses_dbl'] = $player['losses_dbl'] + $wins['wins_2'];
		}
		// Wins where this player is on team 2
		$stmt3 = $conn->prepare('SELECT wins_1,wins_2 FROM series WHERE team_2 = :team_id  AND players = 4');
		$stmt3->execute(array('team_id' => $teams['team_id']));
		while($wins = $stmt3->fetch(PDO::FETCH_ASSOC)) {
			$player['wins_dbl'] = $player['wins_dbl'] + $wins['wins_2'];
			$player['losses_dbl'] = $player['losses_dbl'] + $wins['wins_1'];
		}
	}
	// Ranking Algorithim
	$games_played = $player['wins'] + $player['losses'] + $player['wins_dbl'] + $player['losses_dbl'] + 1;
	$player['points'] = round((((($player['wins']) * .25 - ($player['losses'] * .15)) + (($player['wins_dbl'] * .15) - ($player['losses_dbl'] * .05)) + ($games_played * .5)) / ($games_played / ($games_played * .75))),4);
	
	// Update the points for each player	
	$query = "";
	$stmt4 = $conn->prepare('REPLACE INTO rankings (rank, player_id, points) VALUES (:rank, :player_id, :points)');
	$stmt4->execute(array(
		'rank' => $rank,
		'player_id' => $player['player_id'],
		'points' => $player['points']
	));
	$rank++;

	print_r($player);
}
// Reoder the rankings based on points
$rank = 1;
$stmt = $conn->prepare('SELECT * FROM rankings ORDER BY points DESC');
$stmt->execute();
while($rankings = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$stmt2 = $conn->prepare('REPLACE INTO rankings (rank, player_id, points) VALUES (:rank, :player_id, :points)');
	$stmt2->execute(array(
		'rank' => $rank,
		'player_id' => $rankings['player_id'],
		'points' => $rankings['points']
	));
	$rank++;
}
?>