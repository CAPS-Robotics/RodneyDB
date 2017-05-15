<?php
class Utils {
	
	public static function formatPhoneNum($num) {
		return substr($num, 0, 3) . "-" . substr($num, 3, 3) . "-" . substr($num, 6, 4);
	}
	
	public static function hoursSince($start, $end = -1)
	{
		if ($end < 0) {
			$end = time();
		}
		return ($end - $start) / 60 / 60;
	}

	public static function getRankName($rankNum) {
		$rankStr;
		switch ($rankNum) {
			case 5:
				$rankStr = "Unconfirmed";
				break;
			case 6:
				$rankStr = "Member";
				break;
			case 7:
				$rankStr = "Member+";
				break;
			case 8:
				$rankStr = "Mentor";
				break;
			case 9:
				$rankStr = "Leader";
				break;
			case 10:
				$rankStr = "Administrator";
				break;
			case 11:
				$rankStr = "AlumniAdmin";
				break;
		}
		return $rankStr;
	}
}
?>
