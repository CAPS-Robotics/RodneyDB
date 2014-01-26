<?php
class HomePage extends Page {
	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {
		$content = 
'
<div class="jumbotron">
<img src="assets/img/mustang.svg"></img>
<h1>Rodney</h1>
<p class="lead">Team member management database for <a href="http://mmr2410.com/">FRC Team 2410</a>.</p>
</div>
';
		echo $content;
	}

	public function writePage() {
		self::writePageStart();
		self::writePageContent();
		self::writePageEnd();
	}
}
?>
