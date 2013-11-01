<?php
    $db = mysqli_connect("db430924922.db.1and1.com","dbo430924922","1nn0c3nts_Twa1n","db430924922");
    $res = $db->query("SELECT * FROM `users`");
    while($oldMember = $res->fetch_object()){
        $phone = str_replace("-", "", $oldMember->phonenumber);
        $phone = str_replace("(", "", $oldMember->phonenumber);
        $phone = str_replace(")", "", $oldMember->phonenumber);
        $db->query("INSERT INTO  `new_users` (`rank` ,  `studentId` ,  `name` ,  `email` ,  `hours` ,  `phone` ,  `password` ,  `lastHourLog` ,  `text` ) VALUES ('" . floor($oldMember->rank / 10) . "', '" . $oldMember->checkinid . "',  '" . $oldMember->name . "', '" . $oldMember->email . "', '" . $oldMember->hours."',".$phone."','37a8eec1ce19687d132fe29051dca629d164e2c4958ba141d5','0','".$oldMember->phonetexting."')");
        echo "INSERT INTO  `new_users` (`rank` ,  `studentId` ,  `name` ,  `email` ,  `hours` ,  `phone` ,  `password` ,  `lastHourLog` ,  `text` ) VALUES ('" . floor($oldMember->rank / 10) . "'. '" . $oldMember->checkinid . "'.  '" . $oldMember->name . "'.  '" . $oldMember->email . "'.  '" . $oldMember->hours."'.'".$phone."'.'37a8eec1ce19687d132fe29051dca629d164e2c4958ba141d5'.'0'.'".$oldMember->phonetexting."'\n";
    }
?>
