<?php
date_default_timezone_set('America/New_York');
$today = date('Ymd');
require_once('lib/dbconnect.php');
require_once('lib/series.php');
require_once('lib/series_logs.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Synafoos Series</title>
<link rel="stylesheet" href="css/reset.css" media="all" />
<link href='http://fonts.googleapis.com/css?family=Luckiest+Guy|Nunito:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/styles.css" media="all" />
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]--> 
</head>

<body>
<section id="content" class="home">
	<header role="logo"><h1><a href="/foosball/">syna<span>foos</span></a></h1></header>
	<section id="main">
		<?php
		foreach ($series_arr as $s)
		{
		echo '
		<div class="series'; if ($s['series']['players'] == 4){ echo ' double'; }else{ echo ' single'; } echo' clearfix" series_id="' . $s['series']['series_id'] . '">';
			for ($t = 1; $t <= 2; $t++)
			{
			echo '
			<div class="team">
				<h2>' . $s['team'.$t]['nickname'] . '</h2>
				<div class="players">';
					// Doubles Match
					if ($s['series']['players'] == 4)
					{
					echo '
					<div class="player"><img src="images/' . $s['team'.$t.'_player1']['image'] . '.jpg" alt="' . $s['team'.$t.'_player1']['nickname'] . '" /></div>
					<div class="player"><img src="images/' . $s['team'.$t.'_player2']['image'] . '.jpg" alt="' . $s['team'.$t.'_player2']['nickname'] . '" /></div>';
					}
					// Singles Match
					else
					{
					echo '
					<div class="player"><img src="images/' . $s['team'.$t]['image'] . '.jpg" alt="' . $s['team'.$t]['nickname'] . '" /></div>';
					}
				echo '
				</div>
				<div class="wins">
					<em>' . $s['series']['wins_'.$t] . '</em>
					<span>wins</span>
				</div>
			</div>';
			}
			echo '
			<div class="log_link">
				<a href="/foosball/series.php?s='.$s["series"]["series_id"].'">View/Update Series Log</a>
			</div>
		</div>';
		}
		?>
	</section>
	<aside id="sidebar">
		<?php
		echo '
		<header role="component">
			<h2>Recent Results</h2>
		</header>
		<section class="series_log">
		<div class="series_log_head">
			<div class="winner">Winner</div>
			<div class="loser">Loser</div>
			<div class="date">Date</div>
		</div>';
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
			else if ((date('Ymd',$sl['date_time'] + 1)) == date('Ymd'))
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
		?>
	</aside>
</section>
<!-- Scripts -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="js/winner.js"></script>
</body>
</html>