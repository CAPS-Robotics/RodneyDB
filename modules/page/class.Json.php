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
				var_dump($users);
				$data = array(
				    'success'=>'true',
				    'data'=>$users
				);
				break;
			default:
				$data = array(
				    'success'=>'false'
				);
				break;
		}
		echo json_encode($data);

	}

	public function writePage() {
		header('Content-Type: application/json');
		self::writePageContent();
	}
}
?>
