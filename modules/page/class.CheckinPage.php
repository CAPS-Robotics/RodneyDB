<?php
class CheckinPage extends Page {
	
	private $ritterisms;

	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
		$this->ritterisms = new Ritterisms();
	}

	public function writePageContent() {
		$content = 
'
<script src="assets/js/checkin.js"></script>
<div id="alertbox"></div>  
<div class="jumbotron" style="font-size: medium;">  
<h1>Check-In/Out</h1>

    <input id="studentid" class="form-control input-lg" type="text" placeholder="Student ID" autofocus autocomplete="off">
    <button id="checkin" class="btn btn-primary btn-lg btn-block ladda-button" data-style="slide-up" style="margin-top: 10px;"><span class="ladda-label">Check In/Out</span>

    </button>
</div>
<div class="jumbotron" style="font-size: medium;padding-bottom: 10px;">
<div id="countdown">
</div>
</div>
';
		echo $content;
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
