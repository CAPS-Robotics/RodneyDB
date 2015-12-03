// Rodney 3.0 Dashboard
// Tianyi Ma
// tma02

currentTarget = '#home';
updateInterval = 30000;

jQuery(function($) {
	resize();
	$('.nav-frame').css('overflow-y', 'auto');
	$(window).resize(function() {
		resize();
	});
	if (window.nonav) {
		$('#checkin').find('#popout').remove();
	}
	console.log('Hey curious person! Welcome to Rodney 3.');
	console.log('API on GitHub: https://github.com/CAPS-Robotics/ryAPI');
	console.log('Current API root: ' + apiRoot);
	currentTarget = '#' + (location.hash.length == 0 ? 'home' : location.hash.replace('#', ''));
	$(currentTarget + '-button').addClass('active');
	$('.nav-frame').addClass('background-fade-out');
	$('.user-info, .btn-container, .nav-empty, .nav-bottom, .content-page').addClass('content-fade-in');
	$('.content-frame').scrollTop($(currentTarget).offset().top + $('.content-frame').scrollTop());
	$('.content-page').find('input:last').on('keydown', function(e){if (e.keyCode == 9) e.preventDefault();});
	enableButtons();
	scroll();
	initInfo();
	checkinScript();
	updatePageContent();
	setInterval(updatePageContent, updateInterval);
});

function resize() {
	if (window.nonav) {
		$('.nav-frame').css('display', 'none');
		$('.content-frame').css('width', '100vw');
		$('.content-frame').css('left', '0');
	}
	else {
		$('.content-frame').css('width', $(window).width() - 200);
	}
	$('.nav-empty').css('height', $(window).height() - $('.btn-container').height() - $('.user-info').height() - 35);
	$('.content-page').css('height', $(window).height());
	$('.content-frame').scrollTop($(currentTarget).offset().top + $('.content-frame').scrollTop());
	if ($(window).height() < $('.nav-frame')[0].scrollHeight) {
		$('.nav-bottom').hide();
	}
	else if ($('.nav-bottom').is(':hidden')) {
		$('.nav-bottom').fadeIn(300);
	}
}

function enableButtons() {
	$('.btn-nav').not('#logout-button').not('#profile-button').click(function() {
		$('.content-frame').animate({
			scrollTop: $($(this).attr('target')).offset().top + $('.content-frame').scrollTop()
		}, 500, 'easeOutQuint');
		setTimeout(function(btn) {
			currentTarget = $(btn).attr('target');
			location.hash = currentTarget;
			updatePageContent();
		}, 500, this);
		$('.btn-nav').removeClass('active');
		$('.user-info').removeClass('active');
		$(this).addClass('active');
	});
	$('#logout-button').click(function() {
		window.location.replace('/login');
	});
	$('#settings').find('#update').click(function() {
		if ($(this).html().startsWith('<i')) {
			return;
		}
		var button = this;
		$(this).html('<i class="fa fa-cog fa-spin"></i>');
		$('#settings').find('#loading').removeClass('content-fade-out');
		$('#settings').find('#loading').addClass('content-fade-in');
		var settingsObj = {
			name: $('#settings').find('[name=name]').val(),
			email: $('#settings').find('[name=email]').val(),
			phone: $('#settings').find('[name=phone]').val(),
			gravEmail: $('#settings').find('[name=gravEmail]').val(),
			curPassword: $('#settings').find('[name=curPassword]').val(),
			newPassword: $('#settings').find('[name=newPassword]').val(),
			newPasswordAgain: $('#settings').find('[name=newPasswordAgain]').val()
		};
		apiRequest('settings', [JSON.stringify(settingsObj)], function(res) {
			$('#settings').find('#loading').removeClass('content-fade-in');
			$('#settings').find('#loading').addClass('content-fade-out');
			$(button).html('<i class="fa fa-check-circle"></i>');
			setTimeout(function() {$(button).html('Update');}, 3000);
			if (res.details.success) {
				showCover($('#settings').find('#details').find('.panel-success'), res.details.message, 3000);
			}
			else {
				showCover($('#settings').find('#details').find('.panel-failure'), res.details.message, 3000);
			}
			if (res.grav.success) {
				showCover($('#settings').find('#grav').find('.panel-success'), res.grav.message, 3000);
			}
			else {
				showCover($('#settings').find('#grav').find('.panel-failure'), res.grav.message, 3000);
			}
			if (res.password.success) {
				showCover($('#settings').find('#password').find('.panel-success'), res.password.message, 3000);
			}
			else {
				showCover($('#settings').find('#password').find('.panel-failure'), res.password.message, 3000);
			}
		});
	});
}

function scroll() {
	$('.content-page').scroll(function (event) {
		var scroll = $(this).scrollTop();
		scroll -= 140;
		$(this).find('.page-ico').css('top', 'calc(100% + ' + scroll + 'px)');
	});
}

function initInfo() {
	apiRequest('info', [], function(res) {
		if (res.success) {
			$('#profile-button').find('#first-name').html(res.firstName.toUpperCase());
			$('#profile-button').find('#last-name').html(res.lastName);
			$('#settings').find('[name=name]').val(res.firstName + ' ' + res.lastName);
			$('#settings').find('[name=email]').val(res.email);
			$('#settings').find('[name=phone]').val(res.phone);
			$('#settings').find('#stuid').html('ID: ' + res.stuid);
			//grav-email
		}
		else {
			console.error('Unable to get user information from API.');
		}
	});
}

function checkinScript() {
	$('#checkin').find('[name=id]').keypress(function(event) {
		if (event.which == 13 && !$('#checkin').find('.panel-cover').hasClass('content-fade-in')) {
			$('#checkin').find('#loading').removeClass('content-fade-out');
			$('#checkin').find('#loading').addClass('content-fade-in');
			apiRequest('checkin', [$(this).val()], function(res) {
				$('#checkin').find('[name=id]').val('');
				$('#checkin').find('#loading').removeClass('content-fade-in');
				$('#checkin').find('#loading').addClass('content-fade-out');
				if (res.success) {
					showCover($('#checkin').find('.panel-success'), res.message, 2000);
				}
				else {
					showCover($('#checkin').find('.panel-failure'), res.message, 2000);
				}
			});
		}
	});
}

function showCover(cover, message, timeout) {
	cover.find('.cover-label').html(message);
	cover.removeClass('content-fade-out');
	cover.addClass('content-fade-in');
	setTimeout(function() {
		cover.removeClass('content-fade-in');
		cover.addClass('content-fade-out');
	}, timeout);
}

function updatePageContent() {
	switch(currentTarget) {
		case '#home':
			apiRequest('overview', [], function(res) {
				if (res.success) {
					//total members
					//countdown to next competition
					//your hours last week
					//total hours last week
				}
				else {
					console.error('Unable to get overview from API.');
				}
			});
			break;
		case '#checkin':
			apiRequest('checkedin', [], function(res) {
				if (res.success) {
					$('#checkin').find('#checkin-table tbody').html('');
					res.users.forEach(function(e, i, a) {
						$('#checkin').find('#checkin-table tbody').append('<tr><td class="width-80">' + e.name + '</td><td>' + e.hours + '</td></tr>');
					});
				}
			});
			break;
	}
}
