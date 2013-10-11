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
			$tableStr .= "<tr><td>" . $member['name'] . "<span class='label label-" . ($member['rank'] == 7 ? "success'>Member+" : ($member['rank'] == 8 ? "danger'>Mentor" : ($member['rank'] == 9 ? "warning'>Leader" : ($member['rank'] >= 10 ? "primary'>Administrator" : "default'>Member" ) ) ) ) . "</span></td><td>" . $member['email'] . "</td><td>" . Utils::formatPhoneNum($member['phone']) . "</td>" . ($rank >= 7 ? "<td>" . $member['studentId'] . "</td>" . "<td>" . $member['hours'] . "</td>" . ($rank >= 10 ? "<td><a href='?p=del&id=" . $member['id'] . "'>Delete</a></td>" : "") : "") . "</tr>";
		}
		return $tableStr;
	}

	public function writePage() {
		self::writePageStart();
		self::writePageContent();
		self::writePageEnd();
	}

}
?>
