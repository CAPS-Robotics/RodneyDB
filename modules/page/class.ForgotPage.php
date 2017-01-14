<?php
class ForgotPage extends Page {

	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {

?>

<div class="jumbotron">
	<h1>Reset password</h1>
	<form method="POST" id="resetForm">
		<input class="form-control input-lg" type="text" name="email" placeholder="Email" autofocus>
		<input class="form-control input-lg" type="password" name="password" placeholder="Password">
		<input class="form-control input-lg" type="password" name="checkPassword" placeholder="Confirm password">
		<input class="form-control input-lg" type="text" name="studentId" placeholder="Student ID">
		<div class="btn-group btn-group-justified" style="margin-top: 10px;">
			<a href="#" class="btn btn-lg btn-primary" onclick="$('#resetForm').submit();">Reset password</a>
		</div>
	</form>
</div>

<?php

	}

	public function writePage() {
		self::writePageStart();
		if (array_key_exists("email", $_POST) && array_key_exists("password", $_POST)) {
			if (strlen($_POST['email']) === 0 || strlen($_POST['password']) === 0) {
				self::alert('danger', 'Error!', 'One or more required fields are missing!');
			}
			else if ((strlen($_POST['email']) !== 0 && strlen($_POST['password']) !== 0) && (strlen($_POST['checkPassword']) === 0 || strlen($_POST['studentId']) === 0)) {
				self::alert('danger', 'Error!', 'One or more required fields are missing!');
			}
			else {
				self::resetPassword($_POST['email'], $_POST['password'], $_POST['checkPassword'], $_POST['studentId']);
			}
		}
		self::writePageContent();
		self::writePageEnd();
	}

	public function resetPassword($email, $password, $checkPassword, $studentId) {
		global $core;
		if ($password !== $checkPassword) {
			self::alert('danger', 'Error!', "Passwords don't match!");
			return;
		}
		if (sizeof($core->getUser($email)) === 0) {
			self::alert('danger', 'Error!', "Email is not in use!");
			return;
		}
		if (!is_numeric($studentId)) {
			self::alert('danger', 'Error!', "Student ID is invalid!");
			return;
		}
		$user = $core->getUser($email);
		if ($user['studentId'] != $studentId) {
			self::alert('danger', 'Error!', "Student ID is invalid!");
		}
		if ($core->updatePassword(hash(DB_USER_HASH_ALGO, $password), $core['id'])) {
			self::alert('success', 'Yay!', "Account created.");
		}
		else {
			self::alert('danger', 'Error!', "There was an internal error!");
		}
	}
}
?>
