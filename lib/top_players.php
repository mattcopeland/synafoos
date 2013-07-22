<?php
/*
 * Get the top 10 players
 */
require_once('pdoconnect.php');
$player = array();
$stmt = $conn->prepare('SELECT players.nickname,rankings.rank
		FROM players, rankings
		WHERE players.player_id = rankings.player_id
		ORDER BY rankings.rank ASC 
		LIMIT 10');
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	{
		array_push($player, $row['nickname']);
	}
}
// HTML Payload
echo '
<h2>Top Players</h2>
<section id="top_players">
	<ol>';
	for ($i = 0; $i < 5; ++$i)
	{
		echo '<li>' . $player[$i] . '</li>';
	}
	echo '
	</ol>
	<ol start="6">';
	for ($i = 5; $i < 10; ++$i)
	{
		echo '<li>' . $player[$i] . '</li>';
	}
	echo '
	</ol>
</section>';
?>