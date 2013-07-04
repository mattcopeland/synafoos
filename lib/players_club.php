<?php
/*
 * Get the players
 */
require_once('dbconnect.php');
$players_arr = array();
$i = 0;

$query = "SELECT * FROM players ORDER BY player_id";
$result = mysql_query($query);
if ($result) {
	while($players = mysql_fetch_assoc($result))
	{
		$players_arr[$i] = $players;
		++$i;
	}
}
mysql_close($link);
// HTML Payload
echo '
<h2>Players Club</h2>
<section id="players_club" class="players">';
	foreach ($players_arr as $p) {
		echo '
		<a href="#"><img class="player_img" player_id="' . $p['player_id'] . '" src="img/players/' . $p['image'] . '.jpg" alt="' . $p['nickname'] . '" /></a>';
	}
echo '
</section>';
?>