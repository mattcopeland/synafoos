var synafoos = synafoos || {};

synafoos.matchResult = {
	init: function() {
		$('#match-result-modal-window').on('click','#log_match_result', function (e) {
			synafoos.matchResult.logMatchResult();
		});
		$('.match_result').click(function() {
			synafoos.matchResult.getMatchResult(this);
		});
	},
	dragPlayer: function(player, event) {
		event.dataTransfer.setData('Player', player.id);
	},
	dropInTeam: function(target, event) {
		event.preventDefault();
		var player = event.dataTransfer.getData('Player');
		// there are already 2 players on the team
		// move the first one added back to the pool
		if ($(target).children().size() > 1)
		{
			synafoos.matchResult.pushInPool($(target).children('img:eq(0)'));
		}
		target.appendChild(document.getElementById(player));
		return false;
	},
	pushInPool: function(player) {
		$('#dd_player_pool').append(player);
	},
	dropInPool: function(target, event) {
		event.preventDefault();
		var player = event.dataTransfer.getData('Player');
		target.appendChild(document.getElementById(player));
	},
	logMatchResult: function() {
		var team_1_size = $('#dd_team_1 .dd_team_players').children().size();
		var team_2_size = $('#dd_team_2 .dd_team_players').children().size();
		$('.dd_team').removeClass('error');
		if (team_1_size === 0)
		{
			$('#dd_team_1').addClass('error');
		}
		else if (team_2_size === 0)
		{
			$('#dd_team_2').addClass('error');
		}
		else
		{
			var team_1_player_1 = "",
				team_1_player_2 = "",
				team_2_player_1 = "",
				team_2_player_2 = "";
			team_1_player_1 = $('#dd_team_1 .dd_team_players img:nth-child(1)').attr('player_id');
			team_2_player_1 = $('#dd_team_2 .dd_team_players img:nth-child(1)').attr('player_id');
			if ($('#dd_team_1 .dd_team_players').children().size() > 1) {
				team_1_player_2 = $('#dd_team_1 .dd_team_players img:nth-child(2)').attr('player_id');
			}
			if ($('#dd_team_2 .dd_team_players').children().size() > 1) {
				team_2_player_2 = $('#dd_team_2 .dd_team_players img:nth-child(2)').attr('player_id');
			}
			var dataString = 't1p1='+team_1_player_1+'&t1p2='+team_1_player_2+'&t2p1='+team_2_player_1+'&t2p2='+team_2_player_2;
			// log the win and hide the modal
			$.ajax({
				type: "GET",
				url: "lib/log_match_result.php",
				data: dataString,
				success: function(data){
					$('#match-result-modal-window').modal('hide');
					// Update the recent match results
					$.ajax({
						type: "GET",
						url: "lib/recent_match_results.php",
						success: function(data) {
							console.log(data);
							$('#recent_match_results').html(data);
						}
					});
					// Update the rankings
					$.ajax({
						type: "GET",
						url: "lib/rank.php",
						success: function(data) {
							$.ajax({
								type: "GET",
								url: "lib/top_players.php",
								success: function(data) {
									$('#top_players').html(data);
								}
							});
						}
					});
				}
			});
		}
	},
	getMatchResult: function(self) {
		// Remove data from previous modal
		$('body').on('hidden', '.modal', function () {
			$(this).removeData('modal').children('.modal-body').html('<img src="img/loader.gif" alt="loading..." />');
		});
		// Load new data into modal
		$('#match-result-modal-window').modal({
			remote: '/lib/match_result.php'
		});
	}
};

synafoos.matchResult.init();