<?php
/*
 * Get the players
 */
require_once('pdoconnect.php');

$stmt = $conn->prepare('SELECT * FROM players ORDER BY player_id');
$stmt->execute();
$result = $stmt->fetchAll();

// HTML Payload
echo '
<h2>Players Club</h2>
<section id="players_club" class="players">';
	foreach ($result as $p) {
		echo '
		<a href="#"><img class="player_img" player_id="' . $p['player_id'] . '" src="img/players/' . $p['image'] . '.jpg" alt="' . $p['nickname'] . '" /></a>';
	}
echo '
</section>';
?>