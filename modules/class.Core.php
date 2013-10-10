<?php
class Core {
	
	public $db;

	public function __construct($mysql) {
		session_start();
		global $db;
		$db = $mysql;
	}

	public function login($email, $password) {
		global $db;
		$arr = $db->getArray("SELECT * FROM `" . DB_USER_TABLE . "` WHERE `email`='" . $email . "'");
		if (sizeof($arr) == 1) {
			if ($arr[0]['password'] === $password) {
				return true;
			}
		}
	}

	public function getUser($email) {
		global $db;
		$arr = $db->getArray("SELECT * FROM `" . DB_USER_TABLE . "` WHERE `email`='" . $email . "'");
		if (sizeof($arr) == 1) {
			return $arr[0];
		}
	}

	public function registerUser($email, $password, $name, $studentId, $texting, $phoneNum) {
		global $db;
		$db->query("INSERT INTO `" . DB_USER_TABLE . "`(`studentId`, `name`, `email`, `phone`, `password`, `text`) VALUES ('" . $studentId . "','" . $name . "','" . $email . "','" . $phoneNum . "','" . hash(DB_USER_HASH_ALGO, $password) . "'," . ($texting === "on" ? '1' : '0') . ")");
		if ($db->getMySQLi()->errno) {
			//echo $db->getMySQLi()->error;
			return false;
		}
		return true;
	}

	public function getDB() {
		global $db;
		return $db;
	}

}
?>