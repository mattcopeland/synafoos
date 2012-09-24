<?php
date_default_timezone_set("America/New_York");
require_once('lib/dbconnect.php');
$player_id = $_GET[p] ? $_GET[p] : 1;
require_once('lib/player.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Synafoos Series</title>
<link rel="stylesheet" href="css/reset.css" media="all" />
<link href='http://fonts.googleapis.com/css?family=Luckiest+Guy|Nunito:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/styles.css" media="all" />
<link rel="stylesheet" href="css/player.css" media="all" />
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>

<body>
<section id="content">
	<header role="logo"><h1><a href="/foosball/">syna<span>foos</span></a></h1></header>
	<?php
	echo '
	<section class="player">
		<div class="player_img"><img src="images/' . $player['image'] . '.jpg" alt="' . $player['nickname'] . '" /></div>
		<div class="player_info">
			<div class="nickname">' . $player['nickname'] . '</div>
			<div class="playername">a.k.a. ' . $player['first_name'] . ' ' . $player['last_name'] . '</div>
			<div class="wins_singles">
				<h2>Singles</h2>
				<div class="wins">
					<label>Wins:</label>' . $player['wins'] . '
				</div>
				<div class="loses">
					<label>Loses:</label>' . $player['loses'] . '
				</div>
			</div>
			<div class="wins_doubles">
				<h2>Doubles</h2>
				<div class="wins">
					<label>Wins:</label>' . $player['wins_dbl'] . '
				</div>
				<div class="loses">
					<label>Loses:</label>' . $player['loses_dbl'] . '
				</div>
			</div>
		</div>
	</section>';
	?>
</section>
<!-- Scripts -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="js/winner.js"></script>
</body>
</html>