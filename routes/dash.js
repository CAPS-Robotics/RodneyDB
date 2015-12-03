module.exports = function(app) {
	app.route('/dash')
		.get(function (req, res) {
			res.render('dash', { title: 'Dashboard', message: 'test'});
		});
}