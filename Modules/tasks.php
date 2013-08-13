<?php
//Pathogen Collab
//Copyright © 2010 by Pathogen Studios
//http://www.pathogenstudios.com/

//Possible task statuses
define('TASK_STATE_ACTIVE',0);
define('TASK_STATE_INACTIVE',1);
define('TASK_STATE_DUETODAY',2);
define('TASK_STATE_OVERDUE',3);

//! Resolves the status of a task
function getTaskStatus($startDate,$dueDate)
{
 if ($startDate>time()) {return TASK_STATE_INACTIVE;}
 if ($dueDate>=today() && $dueDate<=tonight()) {return TASK_STATE_DUETODAY;}
 else if ($dueDate<time()) {return TASK_STATE_OVERDUE;}
 return TASK_STATE_ACTIVE;
}

/*! \brief This gadget displays tasks assigned to the current user or all tasks in general.*/
function gadget_tasks()
{
 $out ='';
 $out.='<div class="modebox"><img src="Content/icons/gem_cancel_2.png" alt="">Show All Tasks</div>';
 $out.='<div class="floatbreaker"></div>';
 
 $sql = "SELECT `id`,`title`,`owner`,UNIX_TIMESTAMP(`duedate`) as `duedate`,UNIX_TIMESTAMP(`startdate`) as `startdate`".
 " FROM `".TASK_TABLE."` WHERE `startdate`<FROM_UNIXTIME(".(time()+(60*60*24)).")";
 $result = mysql_query($sql);
 sqlError($result,$sql);
 $count=0;$max=10;$hardmax=$max+5;
 $overduetasks=false;
 while($data=mysql_fetch_assoc($result))
 {
  $count++;
  $icon="bullet_blue.png";
  switch(getTaskStatus($data['startdate'],$data['duedate']))
  {
   case TASK_STATE_INACTIVE:$icon="bullet_gray.png";break;
   case TASK_STATE_DUETODAY:$icon="arrow_small_right.png";break;
   case TASK_STATE_OVERDUE:$icon="bullet_red.png";break;
  }
  //if ($data['startdate']>time()) {$icon="bullet_gray.png";}
  //if ($data['duedate']>=today() && $data['duedate']<=tonight()) {$icon="arrow_small_right.png";}
  //else if ($data['duedate']<time()) {$icon="bullet_red.png";}
  $out.='<div class="item" style="background-image:url(Content/icons/'.$icon.');';
  
  if ($count>$max)
  {
   if ($count>=$hardmax)
   {$out.="display:none;";}
   else
   {$out.="opacity:".(1-($count-$max)/($hardmax-$max)).";";}
  }
  
  if (empty($data['owner'])) {$data['owner']="Nobody";}
  
  $out.='">'.htmlFilter($data['owner']).': <a href="?p=task&t='.$data['id'].'">'.htmlFilter($data['title']).'</a>';
  if ($data['duedate']<today())
  {
   $overduetasks=true;
   $out.=' <img src="Content/icons/error.png" alt="This task is over due!" title="This task is over due!" name="animation_flash">';
  }
  $out.='</div>';
 }
 
 return '<div class="tintedbox divlist"><h1 class="floatleft">Tasks'.
  ($overduetasks?' <span class="sectwarn" name="animation_flash">Over due tasks exist!</span>':'').'</h1>'.$out.'</div>';
}

//A page representing a task
function taskPage()
{
 if (empty($_GET['t']))
 {
  message("No task specified!",1);
  homePage();
  return;
 }
 
 $sql = "SELECT `title`,`details`,`owner`,UNIX_TIMESTAMP(`createdate`) as `createdate`,UNIX_TIMESTAMP(`updatedate`) as `updatedate`,".
 "UNIX_TIMESTAMP(`startdate`) as `startdate`,UNIX_TIMESTAMP(`duedate`) as `duedate` FROM `".TASK_TABLE."` WHERE `id`='".sqlFilter($_GET['t'])."' LIMIT 1";
 $result = mysql_query($sql);
 sqlError($result,$sql);
 
 if (!$result || mysql_num_rows($result)<1)
 {
  message("Unknown task '".$_GET['t']."'!",1);
  homePage();
  return;
 }
 
 $data = mysql_fetch_assoc($result);
 echo('<div class="tintedbox"><h1>'.htmlFilter($data['title']));
 $status="";
 $statuscolor="";
 $statusflash=false;
 switch(getTaskStatus($data['startdate'],$data['duedate']))
 {
  case TASK_STATE_ACTIVE: $status="Task Active";$statuscolor="#8cb359";break;
  case TASK_STATE_DUETODAY: $status="Task Active - Due Today!";$statuscolor="#8cb359";$statusflash=true;break;
  case TASK_STATE_OVERDUE: $status="Task Overdue!";$statuscolor="#b35959";$statusflash=true;break;
 }
 if (!empty($status))
 {
  echo(' <span style="font-size:0.5em;'.(!empty($statuscolor)?'color:'.$statuscolor.';':'').'"');
  if ($statusflash) {echo(' name="animation_flash"');}
  echo('>'.$status.'</span>');
 }
 echo('</h1>');
 echo('<div style="font-size:1.4em;">Assigned to: <b>'.htmlFilter($data['owner']).'</b></div>');
 echo('<div style="font-size:1.1em">');
 echo('Task created: '.date(_LONG_DATE_TIME,$data['createdate']));
 if ($data['updatedate']>0 || 1) {echo(' (Updated '.date(_SHORT_DATE,$data['updatedate']).')');}
 echo('<br>');
 echo(date(_LONG_DATE,$data['startdate']).' - '.date(_LONG_DATE,$data['duedate']));
 echo('</div>');
 echo('<h2>Details:</h2><div class="tintedbox">');// style="max-height:300px;overflow:auto;"
 if (!empty($data['details']))
 {echo(str_replace("\n","<br>",htmlFilter($data['details'])));}
 else
 {echo("There are no details associated with this task.");}
 echo('</div>');
 
 echo('</div>');
}
?>