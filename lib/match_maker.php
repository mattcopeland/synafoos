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
<section id="mm_player_pool" class="players clearfix" ondrop="synafoos.matchMaker.dropInPool(this, event)" ondragenter="return false" ondragover="return false">';
foreach ($players_arr as $p)
{
	echo '
	<img src="img/players/' . $p['image'] . '.jpg" alt="' . $p['nickname'] . '" class="player_img" id="player_' . $p['player_id'] . '" player_id="' . $p['player_id'] . '" draggable="true" ondragstart="synafoos.matchMaker.dragPlayer(this, event)">';
}
echo '
</section>
<div id="mm_matchup">
	<div id="mm_team_1" class="mm_team">
		<div class="mm_team_name"><input type="text" value="" placeholder="Team Name" disabled></div>
		<div class="mm_team_players" ondrop="synafoos.matchMaker.dropInTeam(this, event)" ondragenter="return false" ondragover="return false"></div>
		<div class="mm_drop_zone"><span>1</span><span>2</span></div>
	</div>
	<div id="mm_team_2" class="mm_team">
		<div class="mm_team_name"><input type="text" value="" placeholder="Team Name" disabled></div>
		<div class="mm_team_players" ondrop="synafoos.matchMaker.dropInTeam(this, event)" ondragenter="return false" ondragover="return false"></div>
		<div class="mm_drop_zone"><span>1</span><span>2</span></div>
	</div>
</div>
<div class="btn btn-warning" id="set_match">let\'s do this!</div>';
?>