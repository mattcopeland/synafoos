<?php
//require_once('lib/auth.php');
date_default_timezone_set('America/New_York');
$today = date('Ymd');
require_once('lib/dbconnect.php');
require_once('lib/series.php');
require_once('lib/series_logs.php');
require_once('lib/players.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Synafoos Series</title>
<link rel="stylesheet" href="css/reset.css" media="all" />
<link href='http://fonts.googleapis.com/css?family=Luckiest+Guy|Nunito:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/styles.css" media="all" />
<link rel="stylesheet" href="css/modal.css" media="all" />
<link rel="stylesheet" href="css/player.css" media="all" />
<link rel="stylesheet" href="css/match_maker.css" media="all" />
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]--> 
</head>

<body>
<header role="logo"><h1><a href="/foosball/">synafoos</a></h1></header>
<section id="content" class="clearfix">
	<section id="main">
		<section class="component clearfix">
			<header role="component">
				<h2>Active Series</h2>
			</header>
			<?php
			foreach ($series_arr as $s)
			{
			if ($s['series']['active'] == 1)
			{
			echo '
			<div class="series clearfix" series_id="' . $s['series']['series_id'] . '">';
				for ($t = 1; $t <= 2; $t++)
				{
				echo '
				<div class="team" team_num="' . $t . '">
					<h2>' . $s['team'.$t]['nickname'] . '</h2>
					<div class="wins">
						<span>wins</span>
						<em>' . $s['series']['wins_'.$t] . '</em>
					</div>
				</div>';
				}
				echo '
				<div class="log_link">
					<a href="#" series_id="'.$s["series"]["series_id"].'" num_players="'.$s['series']['players'].'"></a>
				</div>
			</div>';
			}
			}
			?>
		</section>
		<section class="component clearfix">
			<header role="component">
				<h2>Completed Series</h2>
			</header>
			<?php
			foreach ($series_arr as $s)
			{
			if ($s['series']['active'] != 1)
			{
			echo '
			<div class="series clearfix" series_id="' . $s['series']['series_id'] . '">';
				for ($t = 1; $t <= 2; $t++)
				{
				echo '
				<div class="team">
					<h2>' . $s['team'.$t]['nickname'] . '</h2>
					<div class="wins">
						<em>' . $s['series']['wins_'.$t] . '</em>
						<span>wins</span>
					</div>
				</div>';
				}
				echo '
				<div class="log_link">
					<a href="#" series_id="'.$s["series"]["series_id"].'" num_players="'.$s['series']['players'].'"></a>
				</div>
			</div>';
			}
			}
			?>
		</section>
	</section>
	<aside id="sidebar">
		<?php
		echo '
		<section class="component">
			<header role="component">
				<h2>Recent Results</h2>
			</header>
			<section class="series_log">
				<div class="series_log_head">
					<div class="winner">Winner</div>
					<div class="loser">Loser</div>
					<div class="date">Date</div>
				</div>
				<div class="series_log_data">';
					foreach ($series_log_arr as $sl)
					{
					echo '
					<div class="series_log_match'; echo '" series_id="' . $sl['series_id'] . '">
						<div class="winner">' . $sl['winner'] . '</div>
						<div class="loser">' . $sl['loser'] . '</div>
						<div class="date">';
						if (date('Ymd',$sl['date_time']) == date('Ymd'))
						{
							$game_date = "Today";
						}
						else if ((date('Ymd',$sl['date_time']) + 1) == date('Ymd'))
						{
							$game_date = "Yesterday";
						}
						else
						{
							$game_date = date('n/j/Y',$sl['date_time']);
						}
						echo $game_date .'
						</div>
					</div>
					';
					}
				echo '
				</div>
			</section>
		</section>';
		?>
		<?php
		echo '
		<section class="component">
			<header role="component">
				<h2>Players Club</h2>
			</header>
			<section class="players clearfix">';
			foreach ($players_arr as $p)
			{
				echo '
				<a href="#"><img class="player_img" player_id="' . $p['player_id'] . '" src="images/' . $p['image'] . '.jpg" alt="' . $p['nickname'] . '" /></a>';
			}
			?>
			</section>
			<div class="match_maker btn">make a new match</div>
		</section>
	</aside>
</section>
<!-- Modal -->
<div id="modal_mask"></div>
<div id="modal_window"></div>
<!-- Scripts -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="js/winner.js"></script>
<script src="js/series_log.js"></script>
<script src="js/player.js"></script>
<script src="js/modal.js"></script>
<script src="js/match_maker.js"></script>
</body>
</html>