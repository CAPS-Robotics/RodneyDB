var express = require('express');
var app = express();
var config = require('../config.json');

app.set('views', './views');
app.set('view engine', 'jade');

app.config = config;

app.use(express.static('public'));
require('../routes/index.js')(app);
require('../routes/login.js')(app);
require('../routes/dash.js')(app);

var server = app.listen(config.listen.port, function () {
	var host = server.address().address;
	var port = server.address().port;
	console.log('Rodney listening at http://%s:%s', host, port);
});
