<?php
/*
 * Allow a player to log a match result
 */
require_once('pdoconnect.php');

$stmt = $conn->prepare('SELECT * FROM players ORDER BY player_id');
$stmt->execute();
$result = $stmt->fetchAll();

// HTML Payload
echo '
<section id="dd_player_pool" class="players clearfix" ondrop="synafoos.matchResult.dropInPool(this, event)" ondragenter="return false" ondragover="return false">';
foreach ($result as $p)
{
	echo '
	<img src="img/players/' . $p['image'] . '.jpg" alt="' . $p['nickname'] . '" class="player_img" id="player_' . $p['player_id'] . '" player_id="' . $p['player_id'] . '" draggable="true" ondragstart="synafoos.matchResult.dragPlayer(this, event)">';
}
echo '
</section>
<div id="dd_matchup">
	<div id="dd_team_1" class="dd_team">
		<h2>Winner</h2>
		<div class="dd_team_players" ondrop="synafoos.matchResult.dropInTeam(this, event)" ondragenter="return false" ondragover="return false"></div>
		<div class="dd_drop_zone"><span>1</span><span>2</span></div>
	</div>
	<div id="dd_team_2" class="dd_team">
		<h2>Loser</h2>
		<div class="dd_team_players" ondrop="synafoos.matchResult.dropInTeam(this, event)" ondragenter="return false" ondragover="return false"></div>
		<div class="dd_drop_zone"><span>1</span><span>2</span></div>
	</div>
</div>
<div class="btn btn-warning" id="log_match_result">book it!</div>';
?>