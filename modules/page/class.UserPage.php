<?php
class UserPage extends Page {

	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {
		global $core;
		$content = 
'
<div class="jumbotron" style="text-align: left; font-size: medium;">
<h1>' . $core->getUser($_SESSION['email'])['name'] . '<a href="?p=me&edit" class="btn btn-primary btn-xs" style="float: right;">Edit account</a></h1>
<div class="row">
<div class="col-md-4">
<div class="panel panel-info">
<div class="panel-heading">Information</div>
<ul class="list-group" style="line-height: 1;">
<li class="list-group-item">
<span class="badge">' . $core->getUser($_SESSION['email'])['hours'] . '</span>
Hours
</li>
<li class="list-group-item">
<span class="badge">' . $core->getUser($_SESSION['email'])['rank'] . '</span>
Rank
</li>
</ul>
</div>
</div>
<div class="col-md-8">
<div class="panel panel-info">
<div class="panel-heading">Contact Details</div>
<ul class="list-group" style="line-height: 1;">
<li class="list-group-item">
<span class="badge">' . $core->getUser($_SESSION['email'])['email'] . '</span>
Email address
</li>
<li class="list-group-item">
<span class="badge">' . self::formatPhoneNum($core->getUser($_SESSION['email'])['phone']) . '</span>
Phone number
</li>
</ul>
</div>
</div>
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

	public function formatPhoneNum($num) {
		return substr($num, 0, 3) . "-" . substr($num, 3, 3) . "-" . substr($num, 6, 4);
	}
}
?>
