<?php
/*
 * Get the logs of a particular series
 */
date_default_timezone_set('UTC');
require_once('pdoconnect.php');
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
$stmt = $conn->prepare('SELECT players,team_1,team_2,wins_1,wins_2,active FROM series WHERE series_id = :series_id LIMIT 1');
$stmt->execute(array('series_id' => $series_id));
$series_details = $stmt->fetch(PDO::FETCH_ASSOC);

// Doubles Match
if ($series_details["players"] == 4) {
	// Get the team names
	// Team 1
	$stmt = $conn->prepare('SELECT nickname FROM '.$table.' WHERE '.$field.' = :team_1');
	$stmt->execute(array(
		'team_1' => $series_details['team_1']
	));
	$team_1_nickname = $stmt->fetchColumn();
	$series_details['team_1_details']['nickname'] = $team_1_nickname;
	
	// Team 2
	$stmt = $conn->prepare('SELECT nickname FROM '.$table.' WHERE '.$field.' = :team_2');
	$stmt->execute(array(
		'team_2' => $series_details['team_2']
	));
	$team_2_nickname = $stmt->fetchColumn();
	$series_details['team_2_details']['nickname'] = $team_2_nickname;
	
	// Get the nicknames and images for the players on team 1
	$stmt = $conn->prepare('SELECT player_1,player_2 FROM '.$table.' WHERE '.$field.' = :team_1');
	$stmt->execute(array(
		'team_1' => $series_details['team_1']
	));
	$team_1_players = $stmt->fetch(PDO::FETCH_ASSOC);

	$stmt = $conn->prepare('SELECT nickname,image FROM players WHERE player_id = :player_1');
	$stmt->execute(array(
		'player_1' => $team_1_players['player_1']
	));
	$team_1_player_1 = $stmt->fetch(PDO::FETCH_ASSOC);
	$series_details["team_1_details"]["player_1"] = $team_1_player_1;

	$stmt = $conn->prepare('SELECT nickname,image FROM players WHERE player_id = :player_2');
	$stmt->execute(array(
		'player_2' => $team_1_players['player_2']
	));
	$team_1_player_2 = $stmt->fetch(PDO::FETCH_ASSOC);
	$series_details["team_1_details"]["player_2"] = $team_1_player_2;

	// Get the nicknames and images for the players on team 2
	$stmt = $conn->prepare('SELECT player_1,player_2 FROM '.$table.' WHERE '.$field.' = :team_2');
	$stmt->execute(array(
		'team_2' => $series_details['team_2']
	));
	$team_2_players = $stmt->fetch(PDO::FETCH_ASSOC);

	$stmt = $conn->prepare('SELECT nickname,image FROM players WHERE player_id = :player_1');
	$stmt->execute(array(
		'player_1' => $team_2_players['player_1']
	));
	$team_2_player_1 = $stmt->fetch(PDO::FETCH_ASSOC);
	$series_details["team_2_details"]["player_1"] = $team_2_player_1;

	$stmt = $conn->prepare('SELECT nickname,image FROM players WHERE player_id = :player_2');
	$stmt->execute(array(
		'player_2' => $team_2_players['player_2']
	));
	$team_2_player_2 = $stmt->fetch(PDO::FETCH_ASSOC);
	$series_details["team_2_details"]["player_2"] = $team_2_player_2;
}
// Singles Match
else {
	// Get the team names
	// Player 1
	$stmt = $conn->prepare('SELECT nickname,image FROM '.$table.' WHERE '.$field.' = :team_1');
	$stmt->execute(array(
		'team_1' => $series_details['team_1']
	));
	$team_1 = $stmt->fetch(PDO::FETCH_ASSOC);
	$series_details["team_1_details"]["nickname"] = $team_1["nickname"];
	$series_details["team_1_details"]["player_1"]["nickname"] = $team_1["nickname"];
	$series_details["team_1_details"]["player_1"]["image"] = $team_1["image"];
	// Player 2
	$stmt = $conn->prepare('SELECT nickname,image FROM '.$table.' WHERE '.$field.' = :team_2');
	$stmt->execute(array(
		'team_2' => $series_details['team_2']
	));
	$team_2 = $stmt->fetch(PDO::FETCH_ASSOC);
	$series_details["team_2_details"]["nickname"] = $team_2["nickname"];
	$series_details["team_2_details"]["player_1"]["nickname"] = $team_2["nickname"];
	$series_details["team_2_details"]["player_1"]["image"] = $team_2["image"];
}
// HTML Payload
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
$stmt = $conn->prepare('SELECT * FROM series_log WHERE series_id = :series_id ORDER BY log_id DESC');
$stmt->execute(array(
	'series_id' => $series_id
));
while($series_log = $stmt->fetch(PDO::FETCH_ASSOC)) {
	// Get the winner's nickname
	$stmt2 = $conn->prepare('SELECT nickname FROM '.$table.' WHERE '.$field.' = :winner_id');
	$stmt2->execute(array(
		'winner_id' => $series_log['winner_id']
	));
	$winner_nickname = $stmt2->fetchColumn();
	// Get the loser's nickname
	$stmt2 = $conn->prepare('SELECT nickname FROM '.$table.' WHERE '.$field.' = :loser_id');
	$stmt2->execute(array(
		'loser_id' => $series_log['loser_id']
	));
	$loser_nickname = $stmt2->fetchColumn();
	// Add some things to the series log
	$series_log["game_date"] = date("n/j/Y",$series_log["date_time"]);
	$series_log["winner"] = $winner_nickname;
	$series_log["loser"] = $loser_nickname;

	$series_log_arr[$i] = array(
		"series_log" => $series_log,
	);
	++$i;
}

// HTML Payload
$html_payload .= '<section class="series_log series_log_modal"><div class="series_log_head"><div class="winner">Winner</div></div><div class="series_log_data">';

foreach ($series_log_arr as $series) {
	$rescinded = "";
	if ($series["series_log"]["rescinded"] == 1) {
		$rescinded = " rescinded";
	}
	$html_payload .= '<div class="series_log_match' . $rescinded . '" log_id="' . $series["series_log"]["log_id"] . '"><div class="winner">' . $series["series_log"]["winner"] . '</div></div>';
}

$html_payload .= '</div></section>';

echo $html_payload;
?>