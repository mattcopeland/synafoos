var synafoos = synafoos || {};

synafoos.player = {
	init: function() {
		var self = this;
		$('#players_club a .player_img').click(function(e) {
			e.preventDefault();
			self.getPalyerData(this);
		});
	},
	getPalyerData: function(self) {
		var player_id = $(self).attr('player_id');
		var data_string = 'player_id='+player_id+'&type=HTML';
		// Remove data from previous modal
		$('body').on('hidden', '.modal', function () {
			$(this).removeData('modal').children('.modal-body').html('<img src="img/loader.gif" alt="loading..." />');
		});
		// Load new data into modal
		$('#modal-window').modal({
			remote: '/lib/player.php?' + data_string
		});
	}
};

synafoos.player.init();