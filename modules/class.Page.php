<?php
abstract class Page {

	public $navTrigger;
	private $core;

	public function __construct($trigger, $CORE) {
		global $navTrigger, $core;
		$navTrigger = $trigger;
		$core = $CORE;
	}

	public function writePageStart($hideNav = false) {
		global $navTrigger, $core;

?>

<!DOCTYPE html>
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
	<link rel="shortcut icon" sizes="16x16 24x24 32x32 48x48 64x64" href="http://rodney.mmr2410.com/favicon.ico">
</head>
<body>
<?php

if (!$hideNav):

?>

	<div class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="?p=home">Rodney <span class="label label-primary">2.2</span></a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li <?php if ($navTrigger === "home"): ?>class="active"<?php endif; ?>><a href="?p=home">Home</a></li>
					<?php if ($_SESSION['loggedIn']): ?>
						<li class="dropdown <?php if (in_array($navTrigger, ['directory', 'admindir', 'parentdir'])): ?>active<?php endif; ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Directories <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<?php if ($core->getUser($_SESSION['email'])['rank'] >= 8): ?>
									<li <?php if ($navTrigger === "directory"): ?>class="active"<?php endif; ?>><a href="?p=directory">Team Directory</a></li>
									<li <?php if ($navTrigger === "admindir"): ?>class="active"<?php endif; ?>><a href="?p=admindir">Admin Directory</a></li>
									<li <?php if ($navTrigger === "parentdir"): ?>class="active"<?php endif; ?>><a href="?p=parentdir">Parent Directory</a></li>
								<?php endif; ?>
							</ul>
						</li>
					<?php endif; ?>
					<?php if ($_SESSION['loggedIn'] && $core->getUser($_SESSION['email'])['rank'] >= 9): ?>
						<li <?php if ($navTrigger === "checkin"): ?>class="active"<?php endif; ?>><a href="?p=checkin">Check-In</a></li>
						<li <?php if ($navTrigger === "broadcast"): ?>class="active"<?php endif; ?>><a href="?p=broadcast">Send Broadcast</a></li>
					<?php endif; ?>
				</ul>
				<?php if ($_SESSION['loggedIn']): ?>
					<p class="navbar-text pull-right">Signed in as <a href="?p=me" class="navbar-link"><?php echo $core->getUser($_SESSION['email'])['name']; ?></a> | <a href="?p=login" class="navbar-link">Sign out <i class="glyphicon glyphicon-log-out"></i></a></p>
				<?php else: ?>
					<p class="navbar-text pull-right"><a href="?p=login" class="navbar-link">Sign in <i class="glyphicon glyphicon-log-in"></i></a></p>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php endif; ?>

	<div id="wrap">
		<div class="container">

<?php

	}

	abstract function writePageContent();

	abstract function writePage();

	public function writePageEnd() {

?>

		</div>
	</div>

	<div id="footer">
		<div class="container" id="creditContainer">
			<p class="credit">Rodney 2.2 developed by Tony Ma, Dominic Tassio, Thomas Gorham, and Wes Caldwell for <a href="http://mmr2410.com/">FRC Team 2410</a></p>
		</div>
	</div>

	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/stat.js"></script>
</body>
</html>

<?php

	}

	public function alert($level, $title, $text) {
		echo '<div class="alert alert-' . $level . '" style="margin-top: -7px;"><strong>' . $title . '</strong> ' . $text . '</div>';
	}
}
?>
