var default_val = 'Missed someone?';
var status = 1;

$(document).ready(function() {
	
	$('#input_twitter_screen_name').val(default_val);
	
	$('#input_twitter_screen_name').focus(function() {
		if ($(this).val() == default_val) {
			$(this).val('');
		}
	});
	
	$('#input_twitter_screen_name').blur(function() {
		if ($(this).val() == '') {
			$(this).val(default_val);
		}
	});
	
	$('#add form').submit(function() {
		if (status == 1) {
			status = 0;
			$('#input_twitter_screen_name').addClass('loading');
			$('#add button').addClass('inactive');
			var action = $(this).attr('action');
			$.post(action, {
				twitter_screen_name: $('#input_twitter_screen_name').val(),
				token: $('#input_token').val()
			}, function(data) {
				status = 1;
				$('#input_twitter_screen_name').removeClass('loading');
				$('#add button').removeClass('inactive');
				if (data.status == 'error') {
					alert(data.message);
					$('#input_twitter_screen_name').focus();
				}
				else {
					alert(data.message);
					window.location.reload();
				}
			}, 'json');
		}
		return false;
	});
	
});