<?php
/*
 * Get a player
 */
require_once('pdoconnect.php');

$player_id = $_GET['player_id'] ? $_GET['player_id'] : 1;
$type = $_GET['type'] ? $_GET['type'] : 'HTML';

// Get the personal data for this player
$stmt = $conn->prepare('SELECT * FROM players WHERE player_id = :player_id');
$stmt->execute(array('player_id' => $player_id));

$player = $stmt->fetch(PDO::FETCH_ASSOC);

// Initialize this players wins
$player['wins'] = 0;
$player['losses'] = 0;
$player['wins_dbl'] = 0;
$player['losses_dbl'] = 0;

// Get the (2 player) teams of a series this player is a part of
$stmt = $conn->prepare('SELECT team_id FROM teams WHERE player_1 = :player_id OR player_2 = :player_id');
$stmt->execute(array('player_id' => $player_id));

while($teams = $stmt->fetch(PDO::FETCH_ASSOC)) {
	// Get wins (and losses) where this player is on team 1
	$stmt2 = $conn->prepare('SELECT wins_1,wins_2 FROM series WHERE team_1 = :team_id AND players = 4');
	$stmt2->execute(array('team_id' => $teams['team_id']));

	while($wins = $stmt2->fetch(PDO::FETCH_ASSOC)) {
		$player['wins_dbl'] = $player['wins_dbl'] + $wins['wins_1'];
		$player['losses_dbl'] = $player['losses_dbl'] + $wins['wins_2'];
	}
	// Get wins (and losses) where this player is on team 2
	$stmt2 = $conn->prepare('SELECT wins_1,wins_2 FROM series WHERE team_2 = :team_id AND players = 4');
	$stmt2->execute(array('team_id' => $teams['team_id']));

	while($wins = $stmt2->fetch(PDO::FETCH_ASSOC)) {
		$player['wins_dbl'] = $player['wins_dbl'] + $wins['wins_2'];
		$player['losses_dbl'] = $player['losses_dbl'] + $wins['wins_1'];
	}
}
// Get the match wins where this player is on a 2 person team
$stmt = $conn->prepare('SELECT * FROM match_log WHERE team_1_player_1 = :player_id OR team_1_player_2 = :player_id');
$stmt->execute(array('player_id' => $player_id));
while($matches = $stmt->fetch(PDO::FETCH_ASSOC)) {
	if ($matches['team_1_player_2'] != NULL) {
		$player['wins_dbl'] += 1;
	}
}
// Get the match loses where this player is on a 2 person team
$stmt = $conn->prepare('SELECT * FROM match_log WHERE team_2_player_1 = :player_id OR team_2_player_2 = :player_id');
$stmt->execute(array('player_id' => $player_id));
while($matches = $stmt->fetch(PDO::FETCH_ASSOC)) {
	if ($matches['team_2_player_2'] != NULL) {
		$player['losses_dbl'] += 1;
	}
}

// Get individual wins (and losses) from series
// This player is player 2
$stmt = $conn->prepare('SELECT wins_1,wins_2 FROM series WHERE team_1 = :player_id AND players = 2');
$stmt->execute(array('player_id' => $player_id));

while($wins = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$player['wins'] = $player['wins'] + $wins['wins_1'];
	$player['losses'] = $player['losses'] + $wins['wins_2'];
}
// This player is player 2
$stmt = $conn->prepare('SELECT wins_1,wins_2 FROM series WHERE team_2 = :player_id AND players = 2');
$stmt->execute(array('player_id' => $player_id));

while($wins = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$player['wins'] = $player['wins'] + $wins['wins_2'];
	$player['losses'] = $player['losses'] + $wins['wins_1'];
}

// Get individual wins from matches
$stmt = $conn->prepare('SELECT * FROM match_log WHERE team_1_player_1 = :player_id');
$stmt->execute(array('player_id' => $player_id));
while($matches = $stmt->fetch(PDO::FETCH_ASSOC)) {
	if ($matches['team_1_player_2'] == NULL) {
		$player['wins'] += 1;
	}
}

// Get individual losses from matches
$stmt = $conn->prepare('SELECT * FROM match_log WHERE team_2_player_1 = :player_id');
$stmt->execute(array('player_id' => $player_id));
while($matches = $stmt->fetch(PDO::FETCH_ASSOC)) {
	if ($matches['team_2_player_2'] == NULL) {
		$player['losses'] += 1;
	}
}

// Get this player's ranking
$stmt = $conn->prepare('SELECT rank,points FROM rankings WHERE player_id = :player_id');
$stmt->execute(array('player_id' => $player_id));
$ranking = $stmt->fetch(PDO::FETCH_ASSOC);
$player['rank'] = $ranking['rank'];

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
		<div class="losses"><label>Losses:</label>' . $player['losses'] . '</div>
	</div>
	<div class="wins_doubles">
		<h4>Doubles</h4>
		<div class="wins"><label>Wins:</label>' . $player['wins_dbl'] . '</div>
		<div class="losses"><label>Losses:</label>' . $player['losses_dbl'] . '</div>
	</div>
	<div class="rank">
		<h4>Rank</h4>
		<div>' . $player['rank'] . '</div>
	</div>
</section>';
}
?>