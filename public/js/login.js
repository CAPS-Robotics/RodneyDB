// Rodney 3.0 Login
// Tianyi Ma
// tma02

jQuery(function($) {
	$('#password').keyup(function(e) {
    if (e.keyCode == 13) {
    	$('#login-button').click();
    }
  });
	$('#email').keyup(function(e) {
    if (e.keyCode == 13) {
    	$('#login-button').click();
    }
  });
	$('#login-button').click(function() {
		//send request
		var button = this;
		apiRequest('login', [$('#email').val(), $('#password').val()], function(res) {
			if (res.success) {
				transitionToDash();
			}
			else {
				$('#login-frame').addClass('login-fail');
				setTimeout(function() { $('#login-frame').removeClass('login-fail'); }, 550);
				$(button).html('Log in');
			}
		});
				transitionToDash();
		$(this).html('<i class="fa fa-cog fa-spin"></i>');
	});
	$('#create-button').click(function() {
		transitionToCreate();
	});
});

function transitionToDash() {
	$('#login-frame').addClass('fill-screen').html('');
	setTimeout(function() {
		animateDashIn();
	}, 450);
}

function animateDashIn() {
	$('#login-frame').removeClass('fill-screen');
	$('#login-frame').addClass('content-slide-left');
	$('.nav-frame-login').addClass('nav-slide-left');
	setTimeout(function() {
		window.location.replace('/dash');
	}, 350);
}

function transitionToCreate() {
	$('#heading').fadeOut(500);
	$('#login-form').fadeOut(500, function() {
		$('#login-frame').html('<div style="text-align: center; width: 100%; font-size: 300px;"><i style="color: #555;" class="fa fa-cog fa-spin"></i></div>');
		$('#login-frame').fadeIn(250);
		addCreateContent();
	});
	$('#login-frame').addClass('login-to-create');
}

function addCreateContent() {
	var req = new XMLHttpRequest();
	req.open('GET', '/create');
	req.onload = initCreate;
	req.send();
}

function initCreate() {
	var createHtml = 'Unable to grab content... :(';
	if(this.status == 200) {
		createHtml = this.responseText;
	}
	$('#login-frame').html(createHtml);
	$('.create-container').fadeIn(500);
	page = 0;
	maxPage = 4;
	transDone = true;
	$('#next').click(function() {
		if (page < maxPage && transDone) {
			if (page == 0) {
				//check name
				var firstName = $('[name=firstName]').val()
				apiRequest('create', [0, firstName], function(res) {

				});
				$('#name-text').html(firstName);
			}
			else if (page == 1) {
				//check email
			}
			else if (page == 2) {
				//check password
			}
			else if (page == 3) {
				//check id
				$('#next').html('Finish');
			}
			else if (page == 4) {
				//check phone
			}
			page++;
			transDone = false;
			//move to next page when checked
			$('#' + (page - 1)).fadeOut(500, function() {
				$('#' + page).fadeIn(500, function() {
					transDone = true;
				});
			});
		}
		else if (page == maxPage) {
			//send all info and check
			$('.create-container').fadeOut(500, function() {
				$('.create-done').fadeIn(500);
			});
		}
	});
	$('#back').click(function() {
		if (page > 0 && transDone) {
			if (page == maxPage) {
				$('#next').html('Next');
			}
			page--;
			transDone = false;
			$('#' + (page + 1)).fadeOut(500, function() {
				$('#' + page).fadeIn(500, function() {
					transDone = true;
				});
			});
		}
		else if (page == 0 && transDone) {
			window.location.replace('/login');
		}
	});
	$('#login-from-create').click(function() {
		window.location.replace('/login');
	});
}
