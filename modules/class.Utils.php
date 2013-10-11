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
}
?>