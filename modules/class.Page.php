<?php
abstract class Page {

	public $navTrigger;
	private $core;
	private $mustache;

	public function __construct($trigger, $CORE) {
		global $navTrigger, $core, $mustache;
		$navTrigger = $trigger;
		$core = $CORE;
		$mustache = new Mustache_Engine([
			'cache' => dirname(__FILE__).'/tmp/cache/mustache',
			'loader' => new Mustache_Loader_FilesystemLoader(__DIR__.'/views'),
			'partials_loader' => new Mustache_Loader_FilesystemLoader(__DIR__.'/views/partials')
		]);
	}

	public function writePageStart($hideNav = false) {
		global $navTrigger, $core;
		$pageStart = 
'<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="assets/ico/favicon.png">
<title>Rodney</title>
<script src="assets/js/jquery-1.11.0.min.js"></script>
<script src="assets/js/size-fix.js"></script>
<script src="assets/js/spin.min.js"></script>
<script src="assets/js/ladda.min.js"></script>
<script src="assets/js/countdown.min.js"></script>
<link href="assets/css/yeti.min.css" rel="stylesheet">
<link href="assets/css/ladda-themeless.min.css" rel="stylesheet">
<link href="assets/css/theme.css" rel="stylesheet">
</head>
<body>
'.(!$hideNav ? '<div class="navbar navbar-default navbar-fixed-top">
<div class="container">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="?p=home">Rodney <span class="label label-primary">&#x3B2</span></a>
</div>
<div class="collapse navbar-collapse">
<ul class="nav navbar-nav">
<li' . ($navTrigger === "home" ? ' class="active"' : '') . '><a href="?p=home">Home</a></li>
' . ($_SESSION['loggedIn'] ? '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Directories <b class="caret"></b></a><ul class="dropdown-menu">
<li' . ($navTrigger === "directory" ? ' class="active"' : '') . '><a href="?p=directory">Team Directory</a></li>
' . ($core->getUser($_SESSION['email'])['rank'] >= 8 ? '<li' . ($navTrigger === "admindir" ? ' class="active"' : '') . '><a href="?p=admindir">Admin Directory</a></li>' : '') . '
' . ($core->getUser($_SESSION['email'])['rank'] >= 8 ? '<li' . ($navTrigger === "parentdir" ? ' class="active"' : '') . '><a href="?p=parentdir">Parent Directory</a></li>' : '') . '
</ul>' : '' ) . '
' . ($_SESSION['loggedIn'] && $core->getUser($_SESSION['email'])['rank'] >= 9 ? '<li' . ($navTrigger === "checkin" ? ' class="active"' : '') . '><a href="?p=checkin">Check-In</a></li>' : '') . '
' . ($_SESSION['loggedIn'] && $core->getUser($_SESSION['email'])['rank'] >= 9 ? '<li' . ($navTrigger === "broadcast" ? ' class="active"' : '') . '><a href="?p=broadcast">Send Broadcast</a></li>' : '') . '
</ul>
' . ($_SESSION['loggedIn'] ? '<p class="navbar-text pull-right">Signed in as <a href="?p=me" class="navbar-link">' . $core->getUser($_SESSION['email'])['name'] . '</a> | <a href="?p=login" class="navbar-link">Sign out <i class="glyphicon glyphicon-log-out"></i></a></p>' : '<p class="navbar-text pull-right"><a href="?p=login" class="navbar-link">Sign in <i class="glyphicon glyphicon-log-in"></i></a></p>') . '
</div>
</div>
</div>': '').'
<div id="wrap">
<div class="container">';
		echo $pageStart;
	}

	abstract function writePageContent();

	abstract function writePage();

	public function writePageEnd() {
		$pageEnd = 
'</div>
</div>
<div id="footer">
<div class="container" id="creditContainer">
<p class="credit">Rodney 2.1 developed by Tony Ma, Doc Tassio, Thomas Gorham, and Wes Caldwell for <a href="http://mmr2410.com/">FRC Team 2410</a></p>
</div>
</div>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/stat.js"></script>
</body>
</html>';
		echo $pageEnd;
	}

	public function alert($level, $title, $text) {
		echo '<div class="alert alert-' . $level . '" style="margin-top: -7px;"><strong>' . $title . '</strong> ' . $text . '</div>';
	}
}
?>
