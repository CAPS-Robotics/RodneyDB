<?php
class Json extends Page {
	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {
		global $core;
		array_key_exists("r", $_GET);
		switch ($_GET['r']) {
			case "rank":
				$users = $core->fetchAllUsers();
				foreach ($users as &$value) {
					$value = array(
						'name'=>$value['name'],
						'hours'=>$value['hours'],
						'rank'=>$value['rank']
					);
					$hours[] = $value['hours'];
				}
				array_multisort($hours,SORT_DESC,$users);
				$users=array_slice($users,0,10);
				$data = array(
				    'code'=>'true',
				    'data'=>$users
				);
				break;
			case "checkin":
				if(array_key_exists("email", $_SESSION)){
					$rank=$core->getUser($_SESSION['email'])['rank'];
				}else{
					$data = array(
					    'code'=>'false'
					);
					$rank=0;
					break;
				}
				if($rank>=9 && self::checkId($_GET['d'])){
					$data = self::doCheckin($_GET['d']);
				}else{
					$data = array(
					    'code'=>'false'
					);
				}
				break;
			case "edit":
				if(array_key_exists("email", $_SESSION)){
					$rank=$core->getUser($_SESSION['email'])['rank'];
				} else {
					$data = array(
					    'code'=>'failure'
					);
					$rank = 0;
					break;
				}
				if( $rank>=7 ){
					switch($_GET['f']){
						case '0':
							$field = "name";
							break;
						case '1':
							$field = "email";
							break;
						case '2':
							$field = "phone";
							break;
						case '3':
							$field = "studentId";
							break;
						case '4':
							$field = "hours";
							break;
					}
					switch( $core->getDB()->updateDB($_GET['d'], $field, $_GET['n']) ){
						case 0:
							$data = array(
							    'code'=>'nothing'
							);
							break;
						case 1:
							$data = array(
							    'code'=>'success'
							);
							break;
						default:
							$data = array(
							    'code'=>'failure'
							);
							break;
					}
				}else{
					$data = array(
					    'code'=>'failure'
					);
				}
				break;
			default:
				$data = array(
				    'code'=>'false'
				);
				break;
		}
		echo json_encode($data);

	}

	public function checkId($studentId) {
		global $core;
		if (!is_numeric($studentId)) {
			return false;
		}
		else if (sizeof($core->getDB()->getArray("SELECT * FROM `" . DB_USER_TABLE . "` WHERE `studentId`='" . $studentId . "'")) == 0) {
			return false;
		}
		else {
			return true;
		}
	}

	public function doCheckin($studentId) {
		global $core;
		$user = $core->getDB()->getArray("SELECT * FROM `" . DB_USER_TABLE . "` WHERE `studentId`='" . $studentId . "'");
		if (sizeof($user) > 1) {
			return array(
			    'code'=>'problem'
			);
		}
		else {
			$user = $user[0];
			if ($user['lastHourLog'] != 0) {
				if (Utils::hoursSince($user['lastHourLog']) > CHECKIN_MAX) {
					$core->getDB()->query("UPDATE `" . DB_USER_TABLE . "` SET `lastHourLog`='0' WHERE `studentId`='" . $studentId . "'");
					return array(
					    'code'=>'exceed'
					);
				}
				$hours = round(Utils::hoursSince($user['lastHourLog']) + $user['hours'], 3);
				$core->getDB()->query("UPDATE `" . DB_USER_TABLE . "` SET `lastHourLog`='0', `hours`='" . $hours . "' WHERE `studentId`='" . $studentId . "'");
				if ($user['rank'] == 6 && $hours >= 50) {
					$core->getDB()->query("UPDATE `" . DB_USER_TABLE . "` SET `rank`='7' WHERE `id`='" . $user['id'] . "'");
				}
				return array(
				    'code'=>'checkout',
				    'name'=>$user['name'],
				    'hours'=>$hours
				);
			}
			else {
				$core->getDB()->query("UPDATE `" . DB_USER_TABLE . "` SET `lastHourLog`='" . time() . "' WHERE `studentId`='" . $studentId . "'");
				return array(
				    'code'=>'checkin',
				    'name'=>$user['name']
				);
			}
		}
	}
	public function writePage() {
		header('Content-Type: application/json');
		self::writePageContent();
	}
}
?>
