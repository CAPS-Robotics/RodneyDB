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
';
		echo $content;
		$this->ritterisms->write();
	}

	public function writePage() {
		global $core;
		self::writePageStart(true);
		if (array_key_exists("studentId", $_POST)) {
			if (!is_numeric($_POST['studentId'])) {
				self::alert("danger", "Error!", "Student ID is invalid!");
			}
			else if (sizeof($core->getDB()->getArray("SELECT * FROM `" . DB_USER_TABLE . "` WHERE `studentId`='" . $_POST['studentId'] . "'")) == 0) {
				self::alert("danger", "Error!", "Student ID not found!");
			}
			else {
				self::doCheckin($_POST['studentId']);
			}
		}
		self::writePageContent();
		self::writePageEnd();
	}

	public function doCheckin($studentId) {
		global $core;
		$user = $core->getDB()->getArray("SELECT * FROM `" . DB_USER_TABLE . "` WHERE `studentId`='" . $studentId . "'");
		if (sizeof($user) > 1) {
			self::alert("danger", "Error!", "There is a problem with your account. Contact an administrator.");
		}
		else {
			$user = $user[0];
			if ($user['lastHourLog'] != 0) {
				if (Utils::hoursSince($user['lastHourLog']) > CHECKIN_MAX) {
					self::alert("danger", "Error!", "You've been checked in too long! Hours from the last session will not be counted. Input again if you need to be checked in.");
					$core->getDB()->query("UPDATE `" . DB_USER_TABLE . "` SET `lastHourLog`='0' WHERE `studentId`='" . $studentId . "'");
					return;
				}
				$hours = round(Utils::hoursSince($user['lastHourLog']) + $user['hours'], 3);
				$core->getDB()->query("UPDATE `" . DB_USER_TABLE . "` SET `lastHourLog`='0', `hours`='" . $hours . "' WHERE `studentId`='" . $studentId . "'");
				self::alert("success", $user['name'] . ",", "you have checked out. You now have " . $hours . " hours.");
				if ($user['rank'] == 6 && $hours >= 50) {
					$core->getDB()->query("UPDATE `" . DB_USER_TABLE . "` SET `rank`='7' WHERE `id`='" . $user['id'] . "'");
				}
			}
			else {
				$core->getDB()->query("UPDATE `" . DB_USER_TABLE . "` SET `lastHourLog`='" . time() . "' WHERE `studentId`='" . $studentId . "'");
				self::alert("success", $user['name'] . ",", "you are now checked in. Don't forget to check out when the meeting is over!");
			}
		}
	}

}
?>
