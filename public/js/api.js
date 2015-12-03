//Rodney 3.0 API
//Tianyi Ma
//tma02

apiRoot = 'http://192.168.1.5/api/0/';

function apiRequest(method, params, callback) {
	var req = new XMLHttpRequest();
	params.forEach(function(e, i, a) {
		a[i] = window.btoa(e);
	});
	var url = apiRoot + method + '/' + params.join('/');
	req.open('GET', url);
	req.onload = function() {
		if (this.status == 419) {
			console.error('User no longer authenticated.');
			window.location.replace('/login');
			return;
		}
		else if (this.status == 403) {
			console.error('Authenticated user not allowed access to this resource.');
			return;
		}
		callback(jQuery.parseJSON(this.responseText));
	};
	req.send();
}