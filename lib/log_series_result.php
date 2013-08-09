<?php
/*
 * Increase winning teams total by 1
 *
 */
require_once('pdoconnect.php');
$series_id = $_GET['series_id'];
$winning_team = $_GET['winning_team'];
$losing_team = ($winning_team == 1 ? 2 : 1);
$time = time();
$action = $_GET['action'];
// Log a win
if ($action == 'plus') {
	$stmt = $conn->prepare('UPDATE series SET wins_'.$winning_team.' = wins_'.$winning_team.' + 1, last_updated = '.$time.' WHERE series_id = :series_id');
}
// Rescind a win
else if ($action == 'minus') {
	$stmt = $conn->prepare('UPDATE series SET wins_'.$winning_team.' = wins_'.$winning_team.' - 1 WHERE series_id = :series_id');
}
$stmt->execute(array('series_id' => $series_id));

// Make a series inactive
$stmt = $conn->prepare('SELECT wins_'.$winning_team.',wins_goal FROM series WHERE series_id = :series_id');
$stmt->execute(array('series_id' => $series_id));
$series = $stmt->fetch(PDO::FETCH_ASSOC);
// Check to see if the wins goal has been reached but the latest win
if ($series['wins_'.$winning_team] == $series['wins_goal']) {
	$stmt = $conn->prepare('UPDATE series SET active = 0 WHERE series_id = :series_id');
	$stmt->execute(array('series_id' => $series_id));
}

// Update the series log
if ($action == 'plus')
{
	// Get the number of players in this series
	$stmt = $conn->prepare('SELECT players FROM series WHERE series_id = :series_id');
	$stmt->execute(array('series_id' => $series_id));
	$series_players = $stmt->fetchColumn();
	// Get the winning team's id
	$stmt = $conn->prepare('SELECT team_'.$winning_team.' FROM series WHERE series_id = :series_id');
	$stmt->execute(array('series_id' => $series_id));
	$winner_id = $stmt->fetchColumn();
	// Get the losing team's id
	$stmt = $conn->prepare('SELECT team_'.$losing_team.' FROM series WHERE series_id = :series_id');
	$stmt->execute(array('series_id' => $series_id));
	$loser_id = $stmt->fetchColumn();
	// Inser this result into the database
	date_default_timezone_set("America/New_York");
	$stmt = $conn->prepare('INSERT INTO series_log (log_id, series_id, winner_id, loser_id, date_time) VALUES ("", :series_id, '.$winner_id.', '.$loser_id.', '.$time.')');
	$stmt->execute(array('series_id' => $series_id));

	// Now get the winner's info to update the series details
	// Get the last entry in the log, the one we just put in
	$stmt = $conn->prepare('SELECT * FROM series_log ORDER BY log_id DESC LIMIT 1');
	$stmt->execute(array());
	$log = $stmt->fetch(PDO::FETCH_ASSOC);
	$log['date'] = date('n/j/Y',$log['date_time']);
	$log['time'] = date('g:i a',$log['date_time']);

	// Doubles Match
	if ($series_players['players'] == 4) {
		$table = "teams";
		$field = "team_id";
	}
	// Singles Match
	else {
		$table = "players";
		$field = "player_id";	
	}

	// Get the winner's nickname
	$stmt = $conn->prepare('SELECT nickname FROM '.$table.' WHERE '.$field.' = :winner_id');
	$stmt->execute(array('winner_id' => $winner_id));
	$log['winner'] = $stmt->fetchColumn();
	// Get the loser's nickname
	$stmt = $conn->prepare('SELECT nickname FROM '.$table.' WHERE '.$field.' = :loser_id');
	$stmt->execute(array('loser_id' => $loser_id));
	$log['loser'] = $stmt->fetchColumn();
}
// Rescind the victory
else {
	// Get the winner's id from this series
	$stmt = $conn->prepare('SELECT team_'.$winning_team.' FROM series WHERE series_id = :series_id');
	$stmt->execute(array('series_id' => $series_id));
	$winner_id = $stmt->fetchColumn();
	// Get the last log id for this series (that is notg already rescinded)
	$stmt = $conn->prepare('SELECT log_id FROM series_log WHERE series_id = :series_id AND rescinded != 1 AND winner_id = :winner_id ORDER BY log_id DESC LIMIT 1');
	$stmt->execute(array(
		'series_id' => $series_id,
		'winner_id' => $winner_id
	));
	$log['log_id'] = $stmt->fetchColumn();
	// Update the series log to rescind the win
	$stmt = $conn->prepare('UPDATE series_log SET rescinded = 1 WHERE log_id = :log_id');
	$stmt->execute(array('log_id' => $log['log_id']));
}
// Update the winners score 
$stmt = $conn->prepare('SELECT wins_'.$winning_team.' FROM series WHERE series_id = :series_id');
$stmt->execute(array('series_id' => $series_id));
$log['wins'] = $stmt->fetchColumn();
$result = json_encode($log, JSON_NUMERIC_CHECK);
echo $result;
?>