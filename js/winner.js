var synafoos = synafoos || {};

synafoos.winner = {
	init: function() {
		var self = this;
		$('#modal_window').on('click','.team .wins b', function (e) {
			self.updateWins(this);
		});
	},
	updateWins: function(self) {
		var series_id = $(self).closest('section.series_details').attr('series_id');
		var winning_team = $(self).attr('team_num');
		var action = $(self).attr('class');
		var dataString = 'series_id='+series_id+'&winning_team='+winning_team+'&action='+action;
		var wins = $(self).parent().siblings('em').text();
		$(self).parent().siblings('em').html('<img src="images/loader.gif" alt="" />');
		// Update wins total for this team
		$.ajax({
			type: "POST",
			url: "lib/winner.php",
			data: dataString,
			success: function(data) {
				data = jQuery.parseJSON(data);
				// Update the number of wins in the modal
				$(self).parent().siblings('em').html(data.wins);
				// Update the number of wins in the main area
				$('#main .series[series_id='+data.series_id+']').find('.team[team_num='+winning_team+'] em').html(data.wins);
				if (action == "plus") {
					synafoos.winner.logWin(data);
				}
				else {
					synafoos.winner.rescindWin(data);
				}
			}
		});
		return false;
	},
	logWin: function(data) {
		// Update Modal log
		$('.series_log_modal .series_log_data').prepend('<div class="series_log_match" log_id="'+data.log_id+'"><div class="winner">'+data.winner+'</div><div class="date">'+data.date+'</div></div>');
		// Update Recent Results log
		$('aside .series_log_data').prepend('<div class="series_log_match" log_id="'+data.log_id+'"><div class="winner">'+data.winner+'</div><div class="loser">'+data.loser+'</div><div class="date">Today</div></div>');
		$('aside .series_log_data .series_log_match:visible:last').hide();
	},
	rescindWin: function(data) {
		$('.series_log_match[log_id='+data.log_id+']').addClass('rescinded');
	}
}

synafoos.winner.init();