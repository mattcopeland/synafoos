var synafoos = synafoos || {};

synafoos.player = {
	init: function() {
		var self = this;
		$('.players a .player_img').click(function() {
			self.getPalyerData(this);
		});
	},
	getPalyerData: function(self) {
		var markup;
		var player_id = $(self).attr('player_id');
		var dataString = 'player_id='+player_id;
		$.ajax({
			type: "POST",
			url: "lib/player.php",
			data: dataString,
			success: function(data) {
				data = jQuery.parseJSON(data);
				modal_data = '<section class="player"><img class="player_img" src="images/'+data.image+'.jpg" alt="'+ data.nickname+'" /><div class="player_info"><div class="nickname">'+data.nickname+'</div><div class="playername">a.k.a. ' + data.first_name + ' ' + data.last_name + '</div><div class="wins_singles"><h2>Singles</h2><div class="wins"><label>Wins:</label>' + data.wins + '</div><div class="loses"><label>Loses:</label>' + data.loses + '</div></div><div class="wins_doubles"><h2>Doubles</h2><div class="wins"><label>Wins:</label>' + data.wins_dbl + '</div><div class="loses"><label>Loses:</label>' + data.loses_dbl + '</div></div></div></section>';
				synafoos.modal.showModal(self,modal_data);
			}
		});
	}
};

synafoos.player.init();