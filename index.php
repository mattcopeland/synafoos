<?php
//require_once('lib/auth.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Synafoos</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
	<link href="css/synafoos.css" rel="stylesheet" media="screen">
	<link href="css/player.css" rel="stylesheet" media="screen">
	<link href="css/match_maker.css" rel="stylesheet" media="screen">
	<link href='http://fonts.googleapis.com/css?family=Luckiest+Guy|Nunito:400,700' rel='stylesheet' type='text/css'>
</head>
<body>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="#">synafoos</a>
			</div>
		</div>
	</div>

	<div id="content-container" class="container">
		<div class="row">
			<div class="span8">
				<section id="series">
					<?php require_once('lib/series.php'); ?>
				</section>
			</div>
			<div class="span4">
				<section id="recent_results">
					<?php require_once('lib/recent_results.php'); ?>
				</section>
				<?php require_once('lib/top_players.php'); ?>
				<?php require_once('lib/players_club.php'); ?>
				<div class="match_maker btn btn-warning">make a new match</div>
			</div>
		</div>
	</div> <!-- /container -->

	<!-- Modal -->
	<div id="modal-window" class="modal hide fade">
		<div class="modal-body"></div>
	</div>
	<div id="match-maker-modal-window" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h2>Synafoos Match Maker</h2>
		</div>
		<div class="modal-body"></div>
	</div>
	<script src="http://code.jquery.com/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/series_log.js"></script>
	<script src="js/winner.js"></script>
	<script src="js/player.js"></script>
	<script src="js/match_maker.js"></script>
</body>
</html>