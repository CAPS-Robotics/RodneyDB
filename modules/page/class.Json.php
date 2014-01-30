<?php
class HomePage extends Page {
	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {
		$sample = array(
		    'name'=>'My Name',
		    'email'=>'my_email@example.com'
		);
		echo json_encode($sample);
	}

	public function writePage() {
		header('Content-Type: application/json');
		self::writePageContent();
	}
}
?>
