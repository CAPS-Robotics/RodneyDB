<?php
class UserPage extends Page {

	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {
		global $core;?>

<div class="jumbotron" style="text-align: left; font-size: medium;">
	<h1>
		<?php echo $core->getUser($_SESSION['email'])['name']?>
		<a href="?p=me<?php echo (array_key_exists("edit", $_GET) ? "" : "&edit")?>" class="btn btn-primary btn-xs" style="float: right;">
			<?php echo (array_key_exists("edit", $_GET) ? "Done" : "Edit account"); ?>
		</a>
	</h1>
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading">Information</div>
				<ul class="list-group" style="line-height: 1;">
					<li class="list-group-item">
						<span class="badge"><?php echo $core->getUser($_SESSION['email'])['hours']; ?></span> Hours
					</li>
					<li class="list-group-item">
						<span class="badge"><?php echo Utils::getRankName($core->getUser($_SESSION['email'])['rank']); ?></span> Rank
					</li>
				</ul>
			</div>
		</div>
		<div class="col-md-8">
<?php 
		if (array_key_exists("edit", $_GET)) {
?>
			<div class="panel panel-primary">
				<div class="panel-heading">Change Password</div>
				<form method="POST" style="padding: 10px 10px 10px 10px;">
					<input class="form-control input-lg" type="password" name="oldPassword" placeholder="Current password" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
					<input class="form-control input-lg" type="password" name="newPassword" placeholder="New password" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0; border-top-left-radius: 0; border-top-right-radius: 0;">
					<input class="form-control input-lg" type="password" name="checkNewPassword" placeholder="Confirm new password" style="border-top-left-radius: 0; border-top-right-radius: 0;">
					<button type="submit" class="btn btn-primary btn-xs">Change password</button>
				</form>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">Contact Details</div>
				<form method="POST" style="padding: 10px 10px 10px 10px;">
					<input class="form-control input-lg" type="text" name="email" placeholder="Email" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;" value="<?php echo $core->getUser($_SESSION['email'])['email']; ?>">
					<div class="input-group">
						<span class="input-group-addon" style="border-top-left-radius: 0;">
							Receive Texts
							<input type="checkbox" name="texting"<?php echo ($core->getUser($_SESSION['email'])['text'] == 1 ? " checked" : ""); ?>>
						</span>
						<input class="form-control input-lg" type="text" name="phoneNum" placeholder="Phone number" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0; border-top-left-radius: 0; border-top-right-radius: 0;" value="<?php echo Utils::formatPhoneNum($core->getUser($_SESSION['email'])['phone']); ?>">
					</div>
					<input class="form-control input-lg" type="text" name="studentId" placeholder="Student ID" style="border-top-left-radius: 0; border-top-right-radius: 0;" value="<?php echo $core->getUser($_SESSION['email'])['studentId']; ?>">
					<button type="submit" class="btn btn-primary btn-xs">Update details</button>
				</form>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">Parent Contact Details</div>
				<form method="POST" style="padding: 10px 10px 10px 10px;">
					<input class="form-control input-lg" type="text" name="parentName" placeholder=" Parent Name" style="border-top-left-radius: 0; border-top-right-radius: 0;" value="<?php echo $core->getUser($_SESSION['email'])['parentName']; ?>">
					<input class="form-control input-lg" type="text" name="parentEmail" placeholder="Parent Email" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;" value="<?php echo $core->getUser($_SESSION['email'])['parentEmail']; ?>">
					<input class="form-control input-lg" type="text" name="parentPhone" placeholder="Parent Phone Number" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0; border-top-left-radius: 0; border-top-right-radius: 0;" value="<?php echo Utils::formatPhoneNum($core->getUser($_SESSION['email'])['parentPhone']); ?>">
					<button type="submit" class="btn btn-primary btn-xs">Update details</button>
				</form>
			</div>
<?php
		} else {
?> 

			<div class="panel panel-primary">
				<div class="panel-heading">Contact Details</div>
				<ul class="list-group" style="line-height: 1;">
					<li class="list-group-item">
						<span class="badge"><?php echo $core->getUser($_SESSION['email'])['email']; ?></span> Email Address
					</li>
					<li class="list-group-item">
						<span class="badge"><?php echo Utils::formatPhoneNum($core->getUser($_SESSION['email'])['phone']); ?></span> Phone Number
					</li>
					<li class="list-group-item">
						<span class="badge"><?php echo $core->getUser($_SESSION['email'])['studentId']; ?></span> Student ID
					</li>
				</ul>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">Parent Contact Details</div>
				<ul class="list-group" style="line-height: 1;">	
					<li class="list-group-item">
						<span class="badge"><?php echo $core->getUser($_SESSION['email'])['parentName']; ?></span> Parent Name
					</li>
					<li class="list-group-item">
						<span class="badge"><?php echo $core->getUser($_SESSION['email'])['parentEmail']; ?></span> Parent Email Address
					</li>
					<?php
						foreach (explode('|', $core->getUser($_SESSION['email'])['parentPhoners']) as $number) {
					?>
						<li>
							<input class="form-control input-lg" type="text" name="parentPhone" value="<?php echo $number ?>">
						</li>
					<?php
						}
					?>
					<li>
						<input class="form-control input-lg" style="curser: pointer;" type="text" disabled="disabled" placeholder="+1 (000) 000-0000">
					</li>
				</ul>
			</div>

<?php
		}
?>

		</div>
	</div>
</div>

<script src="assets/js/userPage.js"></script>

<?php
		echo $content;
	}

	public function updateContactDetails($email, $formattedPhoneNum, $studentId, $texting) {
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
		if (sizeof($core->getUser($email)) != 0 && $email !== $_SESSION['email']) {
			self::alert('danger', 'Error!', "Email is already registered to another user!");
			return false;
		}
		if ($core->getDB()->getArray("SELECT * FROM `" . DB_USER_TABLE . "` WHERE `studentId`=" . $studentId)[0]['id'] !== $core->getUser($_SESSION['email'])['id']) {
			self::alert('danger', 'Error!', "Student ID is already registered to another user!");
			return false;
		}
		if ($core->getDB()->getArray("SELECT * FROM `" . DB_USER_TABLE . "` WHERE `phone`=" . $phoneNum)[0]['id'] !== $core->getUser($_SESSION['email'])['id']) {
			self::alert('danger', 'Error!', "Phone number is already registered to another user!");
			return false;
		}
		$core->updateContactDetails($email, $phoneNum, ($texting === "on" ? 1 : 0), $studentId, $core->getUser($_SESSION['email'])['id']);
		$_SESSION['email'] = $email;
		return true;
	}

	public function updateParentContactDetails($parentName, $parentEmail, $formattedParentPhone) {
		global $core;
		$parentPhone = str_replace("-", "", $formattedParentPhone);
		if (!is_numeric($parentPhone) || strlen($parentPhone) != 10) {
			self::alert("danger", "Error!", "Parent phone number is invalid!");
			return false;
		}
		$core->updateParentContactDetails($core->getUser($_SESSION['email'])['id'], $parentName, $parentEmail, $parentPhone);
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
		$core->updatePassword(hash(DB_USER_HASH_ALGO, $newPassword), $core->getUser($_SESSION['email'])['id']);
		return true;
	}

	public function writePage() {
		self::writePageStart();
		if (array_key_exists("email", $_POST) && array_key_exists("phoneNum", $_POST) && array_key_exists("studentId", $_POST)) {
			if (self::updateContactDetails($_POST['email'],  $_POST['phoneNum'], $_POST['studentId'], $_POST['texting'])) {
				self::alert("success", "Yay!", "Contact details updated successfully.");
			}
		}
		if (array_key_exists("parentName", $_POST) && array_key_exists("parentEmail", $_POST) && array_key_exists("parentPhone", $_POST)) {
			if (self::updateParentContactDetails($_POST['parentName'],  $_POST['parentEmail'], $_POST['parentPhone'], $_POST['texting'])) {
				self::alert("success", "Yay!", "Parent contact details updated successfully.");
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

}
?>
