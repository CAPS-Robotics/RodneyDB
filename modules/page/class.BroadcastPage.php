<?php
class BroadcastPage extends Page {
	
	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {
		$content = 
'
<div class="jumbotron" style="font-size: medium;">
<h1>Broadcast</h1>
This page is under construction.
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