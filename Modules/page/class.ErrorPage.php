<?php
class ErrorPage extends Page {

	private $error;
	private $details;

	public function __construct($trigger, $core, $ERROR, $DETAILS) {
		parent::__construct($trigger, $core);
		global $error, $details;
		$error = $ERROR;
		$details = $DETAILS;
	}

	public function writePageContent() {
		global $error, $details; 
		$content = 
'
<div class="jumbotron">
<h1>Error - ' . $error . '</h1>
' . $details . '
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