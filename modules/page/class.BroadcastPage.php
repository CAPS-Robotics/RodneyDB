<?php
class BroadcastPage extends Page {
	
	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {
		$content = 
'
<script>
window.onload = function () {
	$("#messageHolder").keyup(function() {
		var charsLeft = (160 - $(this).val().length);
		$("#charCount").html("<span class=\'label label-" + (charsLeft < 10 ? "warning" : (charsLeft <= 0 ? "danger" : "success")) + "\'>" + charsLeft + "</span>");
	});
};
</script>
<div class="jumbotron" style="font-size: medium;">
<h1>Broadcast</h1>
This form will send a SMS message to all members with the receive texts option.
<form method="POST">
<textarea class="form-control" rows="3" id="messageHolder" name="message" placeholder="Message (160 Character limit)"></textarea>
<span style="float: right;" id="charCount"><span class="label label-success">160</span></span>
<button type="submit" class="btn btn-primary btn-lg btn-block" style="margin-bottom: 20px;">Send</button>
</form>
</div>
';
		echo $content;
	}

	public function writePage() {
		self::writePageStart();
		if (array_key_exists("message", $_POST)) {
			$url = 'http://api.tropo.com/1.0/sessions?action=create&token=' . TROPO_MESSAGE_TOKEN . '&numbers=' . $this->getFormattedNumbers() . '&msg=' . $_POST['message'];
  			$xml = simplexml_load_file($url) or $this->alert("danger", "Error!", "Tropo API not responding.");
  			if ((string)$xml->success === "true") {
  				$this->alert("success", "Yay!", "Message broadcasted to all members.");
  			}
  			else {
  				$this->alert("danger", "Error!", "Tropo error.");
  			}
		}
		self::writePageContent();
		self::writePageEnd();
	}

	private function getFormattedNumbers() {
		global $core;
		$numbersStr = "";
		$teamArr = $core->fetchAllUsers();
		foreach ($teamArr as $member) {
			$numbersStr .= ($member['text'] == 1 ? $member['phone'] . '|' : '');
		}
		$numbersStr = substr($numbersStr, 0, strlen($numbersStr) - 1);
		return $numbersStr;
	}
}
?>
