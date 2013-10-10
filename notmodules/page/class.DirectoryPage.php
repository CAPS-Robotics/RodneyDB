<?php
class DirectoryPage extends Page {

	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {
		global $core;
		$content = 
'
<div class="jumbotron">
<h1>Team Directory</h1>
<table class="table table-hover" style="text-align: left; font-size: medium;">
<thead></tr><th>Name</th><th>Email</th><th>Phone Number</th>' . ($core->getUser($_SESSION['email'])['rank'] >= 7 ? '<th>Student ID</th><th>Hours</th>' . ($core->getUser($_SESSION['email'])['rank'] >= 10 ? '<th>Delete</th>' : '') : '') . '</tr></thead>
' . self::getDirectoryTable($core->getUser($_SESSION['email'])['rank']) . '
</table>
</div>
';
		echo $content;
	}

	public function getDirectoryTable($rank) {
		global $core;
		$tableStr = "";
		$teamArr = $core->getDB()->getArray("SELECT * FROM `" . DB_USER_TABLE . "`");
		foreach ($teamArr as $member) {
			$tableStr .= "<tr><td style='" . ($member['rank'] == 7 ? "color: #00FF00" : ($member['rank'] == 8 ? "color: #FF0000" : ($member['rank'] == 9 ? "color: #FFFF00" : ($member['rank'] >= 10 ? "color: #0000FF" : "" ) ) ) ) . "''>" . $member['name'] . "</td><td>" . $member['email'] . "</td><td>" . self::formatPhoneNum($member['phone']) . "</td>" . ($rank >= 7 ? "<td>" . $member['studentId'] . "</td>" . "<td>" . $member['hours'] . "</td>" . ($rank >= 10 ? "<td><a href='#'>Delete</a></td>" : "") : "") . "</tr>";
		}
		return $tableStr;
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
