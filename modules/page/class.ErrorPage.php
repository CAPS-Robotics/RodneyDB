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

?>

<div class="jumbotron">
<h1>Error - <?php echo $error; ?></h1>
	<?php echo $details; ?>
</div>

<?php

	}

	public function writePage() {
		self::writePageStart();
		self::writePageContent();
		self::writePageEnd();
	}
}
?>