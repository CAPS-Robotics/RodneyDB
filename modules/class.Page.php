<?php
abstract class Page {

	public $navTrigger;
	private $core;

	public function __construct($trigger, $CORE) {
		global $navTrigger, $core;
		$navTrigger = $trigger;
		$core = $CORE;
	}

	public function writePageStart() {
		global $navTrigger, $core;
		$pageStart = 
'<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="assets/ico/favicon.png">

<title>Rodney</title>
<link href="assets/css/bootstrap.css" rel="stylesheet">
<style>
body {
padding-top: 50px;
}
html, body{
height: 100%;
}
#wrap {
min-height: 100%;
height: auto !important;
height: 100%;
margin: 0 auto -60px;
padding: 0 0 60px;
}
#footer {
height: 60px;
background-color: #f5f5f5;
}
#creditContainer {
width: auto;
max-width: 680px;
padding: 0 15px;
}
#creditContainer .credit {
margin: 20px 0;
}
.jumbotron {
margin-top: 15px;
padding: 40px 15px;
padding-top: 10px;
text-align: center;
}
</style>
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
<div class="container">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="?p=home">Rodney</a>
</div>
<div class="collapse navbar-collapse">
<ul class="nav navbar-nav">
<li' . ($navTrigger === "home" ? ' class="active"' : '') . '><a href="?p=home">Home</a></li>
' . ($_SESSION['loggedIn'] ? '<li' . ($navTrigger === "directory" ? ' class="active"' : '') . '><a href="?p=directory">Team Directory</a></li>' : '') . '
</ul>
' . ($_SESSION['loggedIn'] ? '<p class="navbar-text pull-right">Signed in as <a href="?p=me" class="navbar-link">' . $core->getUser($_SESSION['email'])['name'] . '</a> | <a href="?p=login" class="navbar-link">Sign out</a></p>' : '<p class="navbar-text pull-right"><a href="?p=login" class="navbar-link">Sign in</a></p>') . '
</div>
</div>
</div>
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
<p class="text-muted credit">Credits will go here</p>
</div>
</div>
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>';
		echo $pageEnd;
	}

	public function alert($level, $title, $text) {
		echo '<div class="alert alert-' . $level . '" style="margin-top: -7px;"><strong>' . $title . '</strong> ' . $text . '</div>';
	}
}
?>