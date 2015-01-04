<?php
class LoginPage extends Page {

	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {
		$content = 
'
<div class="jumbotron">
<h1>Sign in</h1>
<form method="POST" id="loginForm">
<input class="form-control input-lg" type="text" name="email" placeholder="Email" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
<input class="form-control input-lg" type="password" name="password" onkeydown="if (event.keyCode == 13) document.forms[\'loginForm\'].submit();" placeholder="Password" style="border-top-left-radius: 0; border-top-right-radius: 0;">
<div id="registerForm" class="panel-collapse collapse" style="margin-top: 10px;">
<input class="form-control input-lg" type="password" name="checkPassword" placeholder="Confirm password" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
<input class="form-control input-lg" type="text" name="name" placeholder="Full name" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0; border-top-left-radius: 0; border-top-right-radius: 0;">
<input class="form-control input-lg" type="text" name="studentId" placeholder="Student ID" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0; border-top-left-radius: 0; border-top-right-radius: 0;">
<div class="input-group" style="margin-top: -1px;">
<span class="input-group-addon" style="border-top-left-radius: 0;">
Receive Texts
<input type="checkbox" name="texting">
</span>
<input class="form-control input-lg" type="text" name="phoneNum" placeholder="Phone number (xxx-xxx-xxxx)" maxlength="12" style="border-top-right-radius: 0;">
</div>
</div>
<div class="btn-group btn-group-justified" style="margin-top: 10px;">
<a class="btn btn-lg btn-primary btn-block" onClick="document.forms[\'loginForm\'].submit();" id="loginButton">Sign in</a>
<a class="btn btn-lg btn-primary btn-block" data-toggle="collapse" data-target="#registerForm" onClick="$(\'#loginButton\').html(\'Create account\'); $(\'#toggleRegister\').hide();" id="toggleRegister">Create account</a>
</div>
</form>
</div>
';
		echo $content;
	}

	public function writePage() {
		self::writePageStart();
		if (array_key_exists("email", $_POST) && array_key_exists("password", $_POST)) {
			if (strlen($_POST['email']) === 0 || strlen($_POST['password']) === 0) {
				self::alert('danger', 'Error!', 'One or more required fields are missing!');
			}
			else if ((strlen($_POST['email']) !== 0 && strlen($_POST['password']) !== 0) && (strlen($_POST['checkPassword']) === 0 && strlen($_POST['studentId']) === 0 && strlen($_POST['phoneNum']) === 0)) {
				self::authUser($_POST['email'], hash(DB_USER_HASH_ALGO, $_POST['password']));
			}
			else if ((strlen($_POST['email']) !== 0 && strlen($_POST['password']) !== 0) && (strlen($_POST['checkPassword']) === 0 || strlen($_POST['studentId']) === 0 || strlen($_POST['phoneNum']) === 0)) {
				self::alert('danger', 'Error!', 'One or more required fields are missing!');
			}
			else {
				self::createUser($_POST['email'], $_POST['password'], $_POST['checkPassword'], $_POST['name'], $_POST['studentId'], $_POST['texting'], $_POST['phoneNum']);
			}
		}
		self::writePageContent();
		self::writePageEnd();
	}

	public function authUser($email, $password) {
		global $core;
		if ($core->login($email, $password)) {
			self::alert('success', 'Yay!', "Login successful.");
			$_SESSION['loggedIn'] = true;
			$_SESSION['email'] = $email;
			header("Location: ?p=home");
		}
		else {
			self::alert('danger', 'Error!', "Invalid password or email.");
		}
	}

	public function createUser($email, $password, $checkPassword, $name, $studentId, $texting, $phoneNum) {
		global $core;
		if ($password !== $checkPassword) {
			self::alert('danger', 'Error!', "Passwords don't match!");
			return;
		}
		if (!is_null($core->getUser($email))) {
			self::alert('danger', 'Error!', "Email is already registered to another user!");
			return;
		}
		if (!is_numeric($studentId)) {
			self::alert('danger', 'Error!', "Student ID is invalid!");
			return;
		}
		if (sizeof($core->getDB()->getArray("SELECT * FROM `" . DB_USER_TABLE . "` WHERE `studentId`=" . $studentId)) != 0) {
			self::alert('danger', 'Error!', "Student ID is already registered to another user!");
			return;
		}
		$phoneNum = str_replace('-', '', $phoneNum);
		if (strlen($phoneNum) != 10 || !is_numeric($phoneNum)) {
			self::alert('danger', 'Error!', "Phone number is invalid!");
			return;
		}
		if ($core->registerUser($email, hash(DB_USER_HASH_ALGO, $password), $name, $studentId, $texting, $phoneNum)) {
			self::alert('success', 'Yay!', "Account created.");
		}
		else {
			self::alert('danger', 'Error!', "There was an internal error!");
		}
	}
}
?>
