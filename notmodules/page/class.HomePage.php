<?php
class HomePage extends Page {
	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {
		$content = 
'
<div class="jumbotron">
<h1>Stuff</h1>
<p class="lead">will go here.</p>
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
