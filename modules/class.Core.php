<?php
class Core {
	
	public $db;
	private $selectUserFromEmailStmt;
	private $selectUserFromIdStmt;
	private $registerUserStmt;
	private $fetchAllStmt;
	private $updatePasswordStmt;
	private $updateContactStmt;

	public function __construct($mysql) {
		session_start();
		$this->db = $mysql;
		$this->selectUserFromEmailStmt = $this->db->prepare("SELECT * FROM `" . DB_USER_TABLE . "` WHERE `email`=:email");
		$this->selectUserFromIdStmt = $this->db->prepare("SELECT * FROM `" . DB_USER_TABLE . "` WHERE `id`=:id");
		$this->registerUserStmt = $this->db->prepare("INSERT INTO `" . DB_USER_TABLE . "`(`studentId`, `name`, `email`, `phone`, `password`, `text`) VALUES (:studentId,:name,:email,:phone,:password,:text)");
		$this->fetchAllStmt = $this->db->prepare("SELECT * FROM `" . DB_USER_TABLE . "`");
		$this->fetchAllNonMentorsStmt = $this->db->prepare("SELECT * FROM `" . DB_USER_TABLE . "` WHERE `rank`<>8");
		$this->fetchFRCStmt = $this->db->prepare("SELECT * FROM `" . DB_USER_TABLE . "` WHERE `frc`=1");
        $this->fetchFTCStmt = $this->db->prepare("SELECT * FROM `" . DB_USER_TABLE . "` WHERE `ftc`=1");
		$this->updatePasswordStmt = $this->db->prepare("UPDATE `" . DB_USER_TABLE . "` SET `password`=:newPass WHERE `id`=:id");
		$this->updateContactStmt = $this->db->prepare("UPDATE `" . DB_USER_TABLE . "` SET `email`=:email, `phone`=:phone, `text`=:text, `studentId`=:studentId WHERE `id`=:id");
		$this->updateParentContactStmt = $this->db->prepare("UPDATE `" . DB_USER_TABLE . "` SET `parentName`=:parentName, `parentPhone`=:parentPhone, `parentEmail`=:parentEmail WHERE `id`=:id");
	}

	public function login($email, $password) {
		$stmt = $this->selectUserFromEmailStmt;
		$stmt->bindParam(":email", $email);
		$stmt->execute();
		if ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
			if ($res['password'] === $password) {
				return true;
			}
		}
	}

	public function getUser($email) {
		$stmt = $this->selectUserFromEmailStmt;
		$stmt->bindParam(":email", $email);
		$stmt->execute();
		if ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
			return $res;
		}
	}

	public function getUserFromId($id) {
		$stmt = $this->selectUserFromIdStmt;
		$stmt->bindParam(":id", $id);
		$stmt->execute();
		if ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
			return $res;
		}
	}

	public function registerUser($email, $password, $name, $studentId, $texting, $phoneNum) {
		$stmt = $this->registerUserStmt;
		$text = ($texting === "on" ? 1 : 0);
		$stmt->bindParam(":studentId", $studentId);
		$stmt->bindParam(":name", $name);
		$stmt->bindParam(":email", $email);
		$stmt->bindParam(":phone", $phoneNum);
		$stmt->bindParam(":password", $password);
		$stmt->bindParam(":text", $text);
		$stmt->execute();
		return true;
	}

	public function fetchAllUsers() {
		$stmt = $this->fetchAllStmt;
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function fetchAllUsersNotMentors() {
		$stmt = $this->fetchAllNonMentorsStmt;
		$stmt->execute();
		return $stmt->fetchAll();
	}

    public function fetchFRCUsers() {
        $stmt = $this->fetchFRCStmt;
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function fetchFTCUsers() {
        $stmt = $this->fetchFTCStmt;
        $stmt->execute();
        return $stmt->fetchAll();
    }

	public function updatePassword($newPass, $id) {
		$stmt = $this->updatePasswordStmt;
		$stmt->bindParam(":newPass", $newPass);
		$stmt->bindParam(":id", $id);
		$stmt->execute();
		return true;
	}

	public function updateContactDetails($email, $phone, $text, $studentId, $id) {
		$stmt = $this->updateContactStmt;
		$stmt->bindParam(":email", $email);
		$stmt->bindParam(":phone", $phone);
		$stmt->bindParam(":studentId", $studentId);
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":text", $text);
                $stmt->execute();
	}

	public function updateParentContactDetails($parentName, $parentPhone, $parentEmail, $id) {
		$stmt = $this->updateParentContactStmt;
		$stmt->bindParam(":parentName", $parentName);
		$stmt->bindParam(":parentPhone", $parentPhone);
		$stmt->bindParam(":parentEmail", $parentEmail);
		$stmt->bindParam(":id", $id);
                $stmt->execute();
	}

	public function getDB() {
		return $this->db;
	}

}
?>
