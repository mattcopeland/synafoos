<?php
require_once('pdoconnect.php');
$series_arr = array();
$i = 0;

$stmt = $conn->prepare('SELECT series_id FROM series ORDER BY last_updated DESC LIMIT 100');
$stmt->execute();

while($series_id = $stmt->fetchColumn()) {
	$stmt2 = $conn->prepare('SELECT * FROM series WHERE series_id = :series_id');
	$stmt2->execute(array('series_id' => $series_id));
	$series = $stmt2->fetch(PDO::FETCH_ASSOC);
	// Doubles Match
	if ($series['players'] == 4) {		
		// Team 1
		$stmt2 = $conn->prepare('SELECT * FROM teams WHERE team_id = :team_1');
		$stmt2->execute(array('team_1' => $series['team_1']));
		$team_1 = $stmt2->fetch(PDO::FETCH_ASSOC);
		// Player 1
		$stmt2 = $conn->prepare('SELECT * FROM players WHERE player_id = :player_1');
		$stmt2->execute(array('player_1' => $team_1['player_1']));
		$team_1_player_1 = $stmt2->fetch(PDO::FETCH_ASSOC);
		// Player 2
		$stmt2 = $conn->prepare('SELECT * FROM players WHERE player_id = :player_2');
		$stmt2->execute(array('player_2' => $team_1['player_2']));
		$team_1_player_2 = $stmt2->fetch(PDO::FETCH_ASSOC);

		// Team 2
		$stmt2 = $conn->prepare('SELECT * FROM teams WHERE team_id = :team_2');
		$stmt2->execute(array('team_2' => $series['team_2']));
		$team_2 = $stmt2->fetch(PDO::FETCH_ASSOC);
		// Player 1
		$stmt2 = $conn->prepare('SELECT * FROM players WHERE player_id = :player_1');
		$stmt2->execute(array('player_1' => $team_2['player_1']));
		$team_2_player_1 = $stmt2->fetch(PDO::FETCH_ASSOC);
		// Player 2
		$stmt2 = $conn->prepare('SELECT * FROM players WHERE player_id = :player_2');
		$stmt2->execute(array('player_2' => $team_2['player_2']));
		$team_2_player_2 = $stmt2->fetch(PDO::FETCH_ASSOC);
		
		$series_arr[$i] = array(
			"series" => $series,
			"team1" => $team_1,
			"team1_player1" => $team_1_player_1,
			"team1_player2" => $team_1_player_2,
			"team2" => $team_2,
			"team2_player1" => $team_2_player_1,
			"team2_player2" => $team_2_player_2,
		);
	}
	// Singles Matches
	else {
		// Palyer 1
		$stmt2 = $conn->prepare('SELECT * FROM players WHERE player_id = :team_1');
		$stmt2->execute(array('team_1' => $series['team_1']));
		$team_1 = $stmt2->fetch(PDO::FETCH_ASSOC);
		// Palyer 2
		$stmt2 = $conn->prepare('SELECT * FROM players WHERE player_id = :team_2');
		$stmt2->execute(array('team_2' => $series['team_2']));
		$team_2 = $stmt2->fetch(PDO::FETCH_ASSOC);

		$series_arr[$i] = array(
			"series" => $series,
			"team1" => $team_1,
			"team2" => $team_2,
		);
	}
	++$i;	
}

// HTML Payload
echo '
<h2>Active Series</h2>
<div class="row">
	<section id="active_series">';			
	foreach ($series_arr as $s)
	{
		if ($s['series']['active'] == 1)
		{
		echo '
		<div class="span4">
			<div class="series" series_id="' . $s['series']['series_id'] . '" num_players="'.$s['series']['players'].'">';
				for ($t = 1; $t <= 2; $t++)
				{
				echo '
				<div class="team" team_num="' . $t . '">
					<h2>' . $s['team'.$t]['nickname'] . '</h2>
					<div class="wins">
						<span>wins</span>
						<em>' . $s['series']['wins_'.$t] . '</em>
					</div>
				</div>';
				}
			echo '
			</div>
		</div>';
		}
	}
	echo '
	</section>
</div>
<h2>Completed Series</h2>
<div class="row">
	<section id="completed_series">';			
	foreach ($series_arr as $s)
	{
		if ($s['series']['active'] != 1)
		{
		echo '
		<div class="span4">
			<div class="series" series_id="' . $s['series']['series_id'] . '" num_players="'.$s['series']['players'].'">';
				for ($t = 1; $t <= 2; $t++)
				{
				echo '
				<div class="team" team_num="' . $t . '">
					<h2>' . $s['team'.$t]['nickname'] . '</h2>
					<div class="wins">
						<span>wins</span>
						<em>' . $s['series']['wins_'.$t] . '</em>
					</div>
				</div>';
				}
			echo '
			</div>
		</div>';
		}
	}
	echo '
	</section>
</div>';
?>