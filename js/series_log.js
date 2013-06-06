var synafoos = synafoos || {};

synafoos.seriesLog = {
	init: function() {
		var self = this;
		$('.series .log_link a').click(function() {
			self.getSeriesDetails(this);
		});
	},
	getSeriesDetails: function(self) {
		var series_id = $(self).attr('series_id');
		var num_players = $(self).attr('num_players');
		var action = "details";
		var dataString = 'series_id='+ series_id + '&num_players='+ num_players + '&action='+ action;
		$.ajax({
			type: "POST",
			url: "lib/series_log.php",
			data: dataString,
			success: function(data) {
				series_details = jQuery.parseJSON(data);
				modal_data = '<section series_id="' + series_id + '" class="series_details';
				if (series_details.players == 4)
				{
					modal_data += ' double';
				}
				else
				{
					modal_data += ' single';
				}
				modal_data += '"><div class="team"><h2>' + series_details.team_1_details.nickname + '</h2><div class="players"><img class="player_img" src="images/' + series_details.team_1_details.player_1.image + '.jpg" alt="' + series_details.team_1_details.player_1.nickname + '" />';
				if (series_details.players == 4)
				{
					modal_data += '<img class="player_img" src="images/' + series_details.team_1_details.player_2.image + '.jpg" alt="' + series_details.team_1_details.player_2.nickname + '" />';
				}
				modal_data += '</div><div class="wins"><span>wins</span><em>' + series_details.wins_1 + '</em>';
				if (series_details.active === 1)
				{
					modal_data += '<div class="controls"><b class="plus" team_num="1">+</b></div>';
				}
				modal_data += '</div></div><div class="team"><h2>' + series_details.team_2_details.nickname + '</h2><div class="players"><img class="player_img" src="images/' + series_details.team_2_details.player_1.image + '.jpg" alt="' + series_details.team_2_details.player_1.nickname + '" />';
				if (series_details.players == 4)
				{
					modal_data += '<img class="player_img" src="images/' + series_details.team_2_details.player_2.image + '.jpg" alt="' + series_details.team_2_details.player_2.nickname + '" />';
				}
				modal_data +='</div><div class="wins"><span>wins</span><em>' + series_details.wins_2 + '</em>';
				if (series_details.active === 1)
				{
					modal_data += '<div class="controls"><b class="plus" team_num="2">+</b></div>';
				}
				modal_data += '</div></div></section>';
				synafoos.seriesLog.getSeriesLog(self);
			}
		});
		return false;
	},
	getSeriesLog: function(self) {
		var series_id = $(self).attr('series_id');
		var num_players = $(self).attr('num_players');
		var action = "log";
		var dataString = 'series_id='+ series_id + '&num_players='+ num_players + '&action='+ action;
		$.ajax({
			type: "POST",
			url: "lib/series_log.php",
			data: dataString,
			success: function(data) {
				data = jQuery.parseJSON(data);
				modal_data += '<section class="series_log series_log_modal"><div class="series_log_head"><div class="winner">Winner</div><div class="date">Date</div></div><div class="series_log_data">';
				data.forEach(function(series) {
					var rescinded = "";
					if (series.series_log.rescinded == 1){
						rescinded = "rescinded";
					}
					var date = new Date(series.series_log.date_time * 1000);
					var month = date.getDay();
					modal_data += '<div class="series_log_match ' + rescinded + '" log_id="' + series.series_log.log_id + '"><div class="winner">' + series.series_log.winner + '</div><div class="date">' + series.series_log.game_date + '</div></div>';
				});
				modal_data += '</div></section>';
				synafoos.modal.showModal(self,modal_data);
			}
		});
		return false;
	}
}

synafoos.seriesLog.init();