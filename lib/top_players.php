<?php
/*
 * Get the top 10 players
 */
require_once('dbconnect.php');
$player = array();
$query = "SELECT players.nickname,rankings.rank
          FROM players, rankings
          WHERE players.player_id = rankings.player_id
          ORDER BY rankings.rank ASC 
          LIMIT 10";

$result = mysql_query($query);
if($result)
{
	while($row = mysql_fetch_assoc($result))
	{
		array_push($player, $row['nickname']);
	}
}
// HTML Payload
echo '
<h2>Top Palyers</h2>
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