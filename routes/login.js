module.exports = function(app) {
	app.route('/create').get(function (req, res) {
		res.render('create', { title: 'Create', teamNum: app.config.team.number });
	});
	app.route('/login').get(function (req, res) {
		res.render('login', { title: 'Login', teamNum: app.config.team.number });
	});
}