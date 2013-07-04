var synafoos = synafoos || {};

synafoos.seriesLog = {
	init: function() {
		var self = this;
		$('#content-container').on('click','.series', function (e) {
			e.preventDefault();
			self.getSeriesDetails(this);
		});
	},
	getSeriesDetails: function(self) {
		var series_id = $(self).attr('series_id');
		var num_players = $(self).attr('num_players');
		var data_string = 'series_id='+ series_id + '&num_players='+ num_players;
		// Remove data from previous modal
		$('body').on('hidden', '.modal', function () {
			$(this).removeData('modal').children('.modal-body').html('<img src="img/loader.gif" alt="loading..." />');
		});
		// Load new data into modal
		$('#modal-window').modal({
			remote: '/lib/series_log.php?' + data_string
		});
	}
}

synafoos.seriesLog.init();