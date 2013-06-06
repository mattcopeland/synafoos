var synafoos = synafoos || {};

synafoos.modal = {
	showModal: function(modal_trigger,modal_data,modal_loc) {
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();

		//If the modal window and mask don't exist then make it
		if (!$('#modal_mask').length) {
			$('body').append('<div id="modal_mask"></div><div id="modal_window"></div>');
		}

		//Place markup into modal window
		modal_data += '<div id="modal_close">X</div>';
		$('#modal_window').html(modal_data);

		//Set heigth and width to mask to fill up the whole screen
		$('#modal_mask').css({'width':maskWidth,'height':maskHeight}).click(function() {
			synafoos.modal.hideModal();
		});

		$('#modal_close').click(function() {
			synafoos.modal.hideModal();
		});

		$('body').css('overflow', 'hidden');
		
		//transition effect
		$('#modal_mask').fadeIn('fast');
		  
		//Get the window height and width
		var winH = $(window).height();
		var winW = $(window).width();

		//Place the modal window at the center of the trigger
		if (modal_loc) {
			var modal_pos = $(modal_trigger).position();
			$('#modal_window').css('top', (modal_pos.top - $('#modal_window').height() / 2));
			$('#modal_window').css('left', (modal_pos.left - $('#modal_window').width() / 2)); 
		}
		//Set the popup window to center
		else {
			$('#modal_window').css('top', (winH / 2 -  $('#modal_window').height() / 2) + $(window).scrollTop());
			$('#modal_window').css('left', winW / 2 - $('#modal_window').width() / 2); 
		}     
		  
		//transition effect
		$('#modal_window').fadeIn('fast');
	},
	hideModal: function() {
		$('#modal_window,#modal_mask').fadeOut(function(){
			$('body').css('overflow', 'visible');
		});
	}
}