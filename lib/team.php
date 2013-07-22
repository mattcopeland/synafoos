<?php
/*
 * Get a team
 */
require_once('pdoconnect.php');
$player_1 = $_GET['player_1'];
$player_2 = $_GET['player_2'];
$stmt = $conn->prepare('SELECT * FROM teams WHERE (player_1 = :player_1 OR player_2 = :player_1) AND (player_1 = :player_2 OR player_2 = :player_2)');
$stmt->execute(array(
	'player_1' => $player_1,
	'player_2' => $player_2
));
$team = $stmt->fetch(PDO::FETCH_ASSOC);

$result = json_encode($team, JSON_NUMERIC_CHECK);
echo $result;
?>