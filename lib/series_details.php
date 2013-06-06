// Get info details about this series
$query = "SELECT players,team_1,team_2,wins_1,wins_2 FROM series WHERE series_id = $series_id LIMIT 1";
$result = mysql_query($query);
if ($result) {
	$series_details = mysql_fetch_assoc($result);
	// Doubles Match
	if ($series_details["players"] == 4)
	{
		$table = "teams";
		$field = "team_id";
	}
	// Singles Match
	else
	{
		$table = "players";
		$field = "player_id";
	}
	$query = "SELECT nickname FROM $table WHERE $field = $series_details[team_1]";
	$result2 = mysql_query($query);
	if ($result2) {
		$team_1 = mysql_fetch_assoc($result2);
	}
	$query = "SELECT nickname FROM $table WHERE $field = $series_details[team_2]";
	$result2 = mysql_query($query);
	if ($result2) {
		$team_2 = mysql_fetch_assoc($result2);
	}
	// Add some things to the series details
	$series_details["team_1"] = $team_1;
	$series_details["team_2"] = $team_2;
}
$result1 = json_encode($series_details, JSON_NUMERIC_CHECK);