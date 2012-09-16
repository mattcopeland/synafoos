$(document).ready(function() {
	$('.team .wins b').click(function() {
		// Capture this object for later use
		var self = this;
		updateWins(self);
	});
});

function updateWins(self)
{
	var series_id = $(self).closest('div.series').attr('series_id');
	var winning_team = $(self).attr('team_id');
	var action = $(self).attr('class');
	var password = $('#password input[type=password]').val();
	var dataString = 'series_id='+ series_id + '&winning_team=' + winning_team + '&password=' + password + '&action=' + action;
	var wins = $(self).parent().siblings('em').text();
	$(self).parent().siblings('em').html('<img src="images/loader.gif" alt="" />');
	// Verify password for this series
	$.ajax({
		type: "POST",
		url: "lib/auth.php",
		data: dataString,
		success: function(data) {
			if (data == 'allow') {
				// Update wins total for this team
				$.ajax({
					type: "POST",
					url: "lib/winner.php",
					data: dataString,
					success: function(data){
						data = jQuery.parseJSON(data);
						//console.log(data);
						$(self).parent().siblings('em').html(data.wins);
						if (action == "plus")
						{
							logWin(data);
						}
						else
						{
							rescindWin(data);	
						}
					}
				});
			}
			else {
				$(self).parent().siblings('em').html(wins);
				showModal('#password');
			}
		}
	});
	return false;
}

function logWin(data)
{
	console.log(data);
	$('.series_log_head').after('<div class="series_log_match" log_id="'+data.log_id+'"><div class="winner">'+data.winner+'</div><div class="date">'+data.date+'</div><div class="time">'+data.time+'</div></div>');
}

function rescindWin(data)
{
	$('.series_log_match[log_id='+data.log_id+']').addClass('rescinded');
}

function showModal(target) {
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();
	//Set heigth and width to mask to fill up the whole screen
	$('#mask').css({'width':maskWidth,'height':maskHeight});
	  
	//transition effect
	$('#mask').fadeTo("normal", 0.8);
	$('#mask').fadeIn("normal");
	  
	//Get the window height and width
	var winH = $(window).height();
	var winW = $(window).width();
	  
	//Set the popup window to center
	$(target).css('top',  (winH / 2 -  $(target).height() / 2) + $(window).scrollTop());
	$(target).css('left', winW / 2 - $(target).width() / 2);      
	  
	//transition effect
	$(target).fadeTo("normal", 1);
	$(target).fadeIn("normal");
	
	//Set focus to input
	$('#password input').first().focus();
	
	$(target + ' form').submit(function() {
		hideModal(target);
		return false;
	});
}

function hideModal(target) {
	$(target).fadeOut(function() {
		$('#mask').fadeOut();
	});
}