<?php
require_once('lib/dbconnect.php');
require_once('lib/players.php');
?>
<section id="mm_player_pool" class="players clearfix" ondrop="synafoos.matchMaker.dropInPool(this, event)" ondragenter="return false" ondragover="return false">
<?php
foreach ($players_arr as $p)
{
	echo '
	<img src="images/' . $p['image'] . '.jpg" alt="' . $p['nickname'] . '" class="player_img" id="player_' . $p['player_id'] . '" player_id="' . $p['player_id'] . '" draggable="true" ondragstart="synafoos.matchMaker.dragPlayer(this, event)">';
}
?>
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
<div class="btn" id="set_match">set match</div>