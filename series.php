<?php
date_default_timezone_set("America/New_York");
require_once('lib/dbconnect.php');
$series_id = $_GET[s] ? $_GET[s] : NULL;
require_once('lib/series.php');
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
<section id="content">
	<header role="logo"><h1><a href="/foosball/">syna<span>foos</span></a></h1></header>
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
				<div class="player_img"><img src="images/' . $s['team'.$t.'_player1']['image'] . '.jpg" alt="' . $s['team'.$t.'_player1']['nickname'] . '" /></div>
				<div class="player_img"><img src="images/' . $s['team'.$t.'_player2']['image'] . '.jpg" alt="' . $s['team'.$t.'_player2']['nickname'] . '" /></div>';
				}
				// Singles Match
				else
				{
				echo '
				<div class="player_img"><img src="images/' . $s['team'.$t]['image'] . '.jpg" alt="' . $s['team'.$t]['nickname'] . '" /></div>';
				}
			echo '
			</div>
			<div class="wins">
				<em>' . $s['series']['wins_'.$t] . '</em>
				<span>wins</span>';
				if($s['series']['active'] == 1)
				{
				echo '
				<div class="controls">
					<b class="plus" team_id="'.$t.'">&#9650;</b>
					<b class="minus" team_id="'.$t.'">&#9660;</b>
				</div>';
				}
			echo '
			</div>
		</div>';
		}
	echo '
	</div>';
	}
	
	if (count($series_arr) == 1)
	{
	require_once('lib/series_log.php');
	echo '
	<section class="series_log">
		<div class="series_log_head">
			<div class="winner">Winner</div>
			<div class="date">Date</div>
			<div class="time">Time</div>
		</div>';
		foreach ($series_log_arr as $sl)
		{
			$rescinded = false;
			if ($sl['series_log']['rescinded'] == 1)
			{
				$rescinded = true;
			}
		echo '	
		<div class="series_log_match'; if ($rescinded){ echo ' rescinded'; } echo '" log_id="' . $sl['series_log']['log_id'] . '">
			<div class="winner">' . $sl['winner'] . '</div>
			<div class="date">' . date('n/j/Y',$sl['series_log']['date_time']) . '</div>
			<div class="time">' . date('g:i a',$sl['series_log']['date_time']) . '</div>
		</div>';
		}
	echo '
	</div>';
	}
	?>
	<div id="password">
		<form>
			<input type="password" name="password" placeholder="Password" />
			<input type="submit" name="submit" value="Submit" />
		</form>
	</div>
	<div id="mask"></div>
</section>
<!-- Scripts -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="js/winner.js"></script>
</body>
</html>