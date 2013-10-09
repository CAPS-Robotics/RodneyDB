<?php
//Rodney
//Copyright © 2013 by FIRST TEAM 2410
//http://www.mmr2410.com/

function hoursSince($start,$end = -1)
{
 if ($end<0) {$end=time();}
 return ($end-$start)/60/60;
}
function truncHours($hours) {return round($hours,1);}//Allow one decimal precision (EG: for 1.5 hours)

function checkinPage()
{
 global $currentUser;
 if (!canStartCheckin($currentUser['rank'])) {message("Access denied.",1);return;}
 $_SESSION['restrictpage']="checkin";
 global $noNavBar;
 $noNavBar = true;
 $stuidDefault = "Student ID";
 
 if (getMode()=="checkin" && !empty($_POST['stuid']) && $_POST['stuid']!=$stuidDefault)
 {
  if ($_POST['stuid']=="12345" || isset($_SESSION['alwaysrickroll']))
  {
   message("Rickroll is disabled due to abuse. Don't keep trying, nothing to see here.",1,1);
  }
  $sql = "SELECT `id`,`name`,`rank`,UNIX_TIMESTAMP(`checkin`) as `unixcheckin`,`hours` FROM `".USER_TABLE."` WHERE `checkinid`='".$_POST['stuid']."' LIMIT 1";
  $res = mysql_query($sql);
  sqlError($res,$sql);
  if (mysql_num_rows($res)<=0)
  {
   message("Unknown user ID",1,1);
  }
  else
  {
   $data = mysql_fetch_assoc($res);
   $checkingout = false;
   
   if (!$data['unixcheckin']<=0)//Already checked in.
   {
    if (hoursSince($data['unixcheckin'])>CHECKIN_MAX)//Someone forgot to check out.
    {message("You did not check out yesterday, your time accumulated yesterday will not be counted.",1,1);}
    else
    {$checkingout=true;}
   }
   
   if (!$checkingout)
   {//Checking in:
    $sql = "UPDATE `".USER_TABLE."` set `checkin` = FROM_UNIXTIME(".time().") WHERE `id`='".$data['id']."'";
    $res = mysql_query($sql);
    sqlError($res,$sql);
    if (!$res)
    {message("There was an issue checking you in, please notify your administrator.",1,1);}
    else
    {
     message("Thank you, <b>".$data['name']."</b>, you are now checked in, don't forget to check out when the meeting is over!",0,1);
    }
   }
   else
   {//Checking out:
    $newTotal = $data['hours'] + hoursSince($data['unixcheckin']);
    $newRank = 0;
    if ($newTotal>=50 && $data['rank']>=50 && $data['rank']<60) {$newRank=$data['rank']+10;}
    
    $sql = "UPDATE `".USER_TABLE."` SET `checkin`=FROM_UNIXTIME(0), `hours`='".$newTotal."'".($newRank==0?"":", `rank`='".$newRank."'")." WHERE `id`='".$data['id']."'";
    $res = mysql_query($sql);
    sqlError($res,$sql);
    if (!$res)
    {message("There was an issue checking you out.",1,1);}
    else
    {
     $msg = '<span class="center">Thank you, <b>'.$data['name'].'</b>, you have checked out and have accumulated a total of '.truncHours($newTotal).' hours.';
     if ($newRank!=0) {$msg.='<br><span style="font-size:2em;">You have automatically been upgraded from '.getRankName($data['rank']).' ('.$data['rank'].') to rank '.getRankName($newRank).' ('.$newRank.')!</span>';}
     message($msg,0,1);
    }
   }
  }
 }
 ?>
 <form class="center tintedbox" method="post" action="">
  <h1>Check In/Out</h1>
  <input type="hidden" name="mode" value="checkin">
  <input type="text" name="stuid" value="<?php echo($stuidDefault); ?>" class="autoclear" onfocus="clearInputBox(this)"><br><br>
  <input type="submit" value="Check In/Out">
 </form>
 <div class="tintedbox center"><h1>HOUR COMPENSATION RULES</h1>
 If you have hours which did not get logged, then these are the rules for regaining your hours:<br>
 <b>Saturdays and Snowdays:</b> You can get them back if you notify me during the next meeting.<br>
 <b>Normal 6-8:30 meetings:</b> You will not receive your hours back.<br>
 <b>Off-campus events and other situations where checkin or out is not possible:</b> You will get your hours back as long as you report them in a timely manner.<br>
</div>
 <div class="tintedbox center"><h2>Bottom Five Members:</h2>
 <?php
 $res = mysql_query("SELECT `name`,`hours` FROM `".USER_TABLE."` WHERE `hours` <> 0 ORDER BY `hours` ASC LIMIT 0,5");
 if(!$res){
 } else{
 echo('<table><tr><th>Name</th><th>Hours</th></tr>');
 while($data = mysql_fetch_assoc($res))
  {
	echo('<tr><td>'.$data['name'].'</td><td>'.$data['hours'].'</td></tr>');
  }
 echo('</table>');
 }
 ?>
 </div>
 <?php
}
?>
