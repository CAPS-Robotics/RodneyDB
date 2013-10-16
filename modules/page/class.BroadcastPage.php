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
		$("#charCount").html(160 - $(this).val().length);
	});
};
</script>
<div class="jumbotron" style="font-size: medium;">
<h1>Broadcast</h1>
<textarea class="form-control" rows="3" id="messageHolder" placeholder="Message (160 Character limit)"></textarea>
<span style="float: right;" id="charCount">160</span>
<button type="button" class="btn btn-primary btn-lg btn-block" style="margin-bottom: 20px;">Send</button>
<div class="panel panel-info">
<div class="panel-heading">Rodney API</div>
Details soon
</div>
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