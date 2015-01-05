<?php
class CheckinPage extends Page {
	
	private $ritterisms;

	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
		$this->ritterisms = new Ritterisms();
	}

	public function writePageContent() {

?>

</div>
<script src="assets/js/checkin.js"></script>
<script src="assets/js/flipclock.min.js"></script>
<div class="container" style="position: relative;">
	<div id="alertbox"></div>
</div>

<div class="container" style="margin-top: 55px;">
	<div class="jumbotron" style="font-size: medium;">  
		<h1>Check-In/Out</h1>

    	<input id="studentid" class="form-control input-lg" type="text" placeholder="Student ID" autofocus autocomplete="off">
    	<button id="checkin" class="btn btn-primary btn-lg btn-block ladda-button" data-style="slide-up" style="margin-top: 10px;">
    		<span class="ladda-label">Check In/Out</span>
    	</button>
	</div>

	<div class="jumbotron" style="font-size: medium;padding-bottom: 10px;">
		<div id="clock"></div>
	</div>
</div>

<?php

		$this->ritterisms->write();
	}

	public function writePage() {
		global $core;
		self::writePageStart(true);
		self::writePageContent();
		self::writePageEnd();
	}

}
?>
