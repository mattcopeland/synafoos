<?php
/*
 * Log a match result (that is not part of a defined series)
 */
$time = time();
require_once('pdoconnect.php');
$team_1_player_1 = $_GET['t1p1'];
$team_2_player_1 = $_GET['t2p1'];
$team_1_player_2 = $_GET['t1p2'];
$team_2_player_2 = $_GET['t2p2'];

if ($team_1_player_2  == "") {
	$team_1_player_2 = NULL;
}
if ($team_2_player_2  == "") {
	$team_2_player_2 = NULL;
}

$stmt = $conn->prepare('INSERT INTO match_log (match_id, team_1_player_1, team_1_player_2, team_2_player_1, team_2_player_2, date_time) VALUES ("", :team_1_player_1, :team_1_player_2, :team_2_player_1, :team_2_player_2 , :time)');
$stmt->execute(array(
	'team_1_player_1' => $team_1_player_1,
	'team_1_player_2' => $team_1_player_2,
	'team_2_player_1' => $team_2_player_1,
	'team_2_player_2' => $team_2_player_2,
	'time' => $time
));
?>