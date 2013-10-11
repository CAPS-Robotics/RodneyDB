<?php
class UserPage extends Page {

	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {
		global $core;
		$content = 
'<div class="jumbotron" style="text-align: left; font-size: medium;">
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
<div class="col-md-8">';
		if (array_key_exists("edit", $_GET)) {
			$content .= 
'<div class="panel panel-info">
<div class="panel-heading">Change Password</div>
<form method="POST" style="padding: 10px 10px 10px 10px;">
<input class="form-control input-lg" type="password" name="oldPassword" placeholder="Current password" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
<input class="form-control input-lg" type="password" name="newPassword" placeholder="New password" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0; border-top-left-radius: 0; border-top-right-radius: 0;">
<input class="form-control input-lg" type="password" name="checkNewPassword" placeholder="Confirm new password" style="border-top-left-radius: 0; border-top-right-radius: 0;">
<button type="submit" class="btn btn-primary btn-xs">Change password</button>
</form>
</div>
<div class="panel panel-info">
<div class="panel-heading">Contact Details</div>
<form method="POST" style="padding: 10px 10px 10px 10px;">
<input class="form-control input-lg" type="text" name="email" placeholder="Email" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;" value="' . $core->getUser($_SESSION['email'])['email'] . '">
<input class="form-control input-lg" type="text" name="phoneNum" placeholder="Phone number" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0; border-top-left-radius: 0; border-top-right-radius: 0;" value="' . self::formatPhoneNum($core->getUser($_SESSION['email'])['phone']) . '">
<input class="form-control input-lg" type="text" name="studentId" placeholder="Student ID" style="border-top-left-radius: 0; border-top-right-radius: 0;" value="' . $core->getUser($_SESSION['email'])['studentId'] . '">
<button type="submit" class="btn btn-primary btn-xs">Update details</button>
</form>
</div>';
		}
		else {
			$content .= 
'<div class="panel panel-info">
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
</div>';
		}
		$content .= 
'</div>
</div>
</div>';
		echo $content;
	}

	public function updateContactDetails($email, $formattedPhoneNum, $studentId) {
		global $core;
		$phoneNum = str_replace("-", "", $formattedPhoneNum);
		if (!is_numeric($studentId)) {
			self::alert("danger", "Error!", "Student ID is invalid!");
			return false;
		}
		if (!is_numeric($phoneNum) || strlen($phoneNum) != 10) {
			self::alert("danger", "Error!", "Phone number is invalid!");
			return false;
		}
		if (!is_null($core->getUser($email)) && $email !== $_SESSION['email']) {
			self::alert('danger', 'Error!', "Email is already registered to another user!");
			return false;
		}
		if ($core->getDB()->getArray("SELECT * FROM `" . DB_USER_TABLE . "` WHERE `studentId`=" . $studentId)[0]['id'] !== $core->getUser($_SESSION['email'])['id']) {
			self::alert('danger', 'Error!', "Student ID is already registered to another user!");
			return false;
		}
		$core->getDB()->query("UPDATE `" . DB_USER_TABLE . "` SET `email`='" . $core->getDB()->getMySQLi()->real_escape_string($email) . "', `phone`='" . $core->getDB()->getMySQLi()->real_escape_string($phoneNum) . "', `studentId`='" . $core->getDB()->getMySQLi()->real_escape_string($studentId) . "' WHERE `id`='" . $core->getUser($_SESSION['email'])['id'] . "'");
		$_SESSION['email'] = $core->getDB()->getMySQLi()->real_escape_string($email);
		return true;
	}

	public function updatePassword($oldPassword, $newPassword, $checkNewPassword) {
		global $core;
		if (hash(DB_USER_HASH_ALGO, $oldPassword) !== $core->getUser($_SESSION['email'])['password']) {
			self::alert("danger", "Error!", "Current password does not match!");
			return false;
		}
		if ($newPassword !== $checkNewPassword) {
			self::alert("danger", "Error!", "New passwords don't match!");
			return false;
		}
		$core->getDB()->query("UPDATE `" . DB_USER_TABLE . "` SET `password`='" . hash(DB_USER_HASH_ALGO, $newPassword) . "' WHERE `id`='" . $core->getUser($_SESSION['email'])['id'] . "'");
		return true;
	}

	public function writePage() {
		self::writePageStart();
		if (array_key_exists("email", $_POST) && array_key_exists("phoneNum", $_POST) && array_key_exists("studentId", $_POST)) {
			if (self::updateContactDetails($_POST['email'],  $_POST['phoneNum'], $_POST['studentId'])) {
				self::alert("success", "Yay!", "Contanct details updated successfully.");
			}
		}
		if (array_key_exists("oldPassword", $_POST) && array_key_exists("newPassword", $_POST) && array_key_exists("checkNewPassword", $_POST)) {
			if (self::updatePassword($_POST['oldPassword'],  $_POST['newPassword'], $_POST['checkNewPassword'])) {
				self::alert("success", "Yay!", "Password updated successfully.");
			}
		}
		self::writePageContent();
		self::writePageEnd();
	}

	public function formatPhoneNum($num) {
		return substr($num, 0, 3) . "-" . substr($num, 3, 3) . "-" . substr($num, 6, 4);
	}
}
?>
