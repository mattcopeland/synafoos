<?php
/*
 * Get the logs of a 10 most recent games
 */
date_default_timezone_set('America/New_York');
require_once('pdoconnect.php');

$games_log_arr = array();
$i = 0;

$stmt = $conn->prepare('SELECT * FROM match_log ORDER BY match_id DESC LIMIT 5');
$stmt->execute();
while($games_log = $stmt->fetch(PDO::FETCH_ASSOC)) {
	// Get the winners nickname
	$stmt2 = $conn->prepare('SELECT nickname FROM players WHERE player_id = :player_id');
	$stmt2->execute(array('player_id' => $games_log['team_1_player_1']));
	$winner = $stmt2->fetchColumn();
	$games_log["winner_1"] = $winner;

	if ($games_log['team_1_player_2'] !== NULL)
	{
		$stmt2 = $conn->prepare('SELECT nickname FROM players WHERE player_id = :player_id');
		$stmt2->execute(array('player_id' => $games_log['team_1_player_2']));
		$winner = $stmt2->fetchColumn();
		$games_log["winner_2"] = $winner;
	}
	// Get the losers nickname
	$stmt2 = $conn->prepare('SELECT nickname FROM players WHERE player_id = :player_id');
	$stmt2->execute(array('player_id' => $games_log['team_2_player_1']));
	$winner = $stmt2->fetchColumn();
	$games_log["loser_1"] = $winner;

	if ($games_log['team_2_player_2'] !== NULL)
	{
		$stmt2 = $conn->prepare('SELECT nickname FROM players WHERE player_id = :player_id');
		$stmt2->execute(array('player_id' => $games_log['team_2_player_2']));
		$winner = $stmt2->fetchColumn();
		$games_log["loser_2"] = $winner;
	}

	$games_log_arr[$i] = $games_log;
	++$i;
}
// HTML Payload
echo '
<h2>Recent Match Results</h2>
<section class="series_log">
	<div class="series_log_head">
		<div class="winner">Winner</div>
		<div class="loser">Loser</div>
	</div>
	<div class="series_log_data">';
		foreach ($games_log_arr as $gl)
		{
		echo '
		<div class="series_log_match'; echo '" match_id="' . $gl['match_id'] . '">
			<div class="winner">' . $gl['winner_1'];
			if (isset($gl['winner_2'])) {
				echo ' / ' . $gl['winner_2'];
			}
			echo '
			</div>
			<div class="loser">' . $gl['loser_1'];
			if (isset($gl['loser_2'])) {
				echo ' / ' . $gl['loser_2'];
			}
			echo '
			</div>
		</div>
		';
		}
	echo '
	</div>
</section>';
?>