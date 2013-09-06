<?php
//Rodney
//Copyright © 2013 by FIRST TEAM 2410
//http://www.mmr2410.com/

function getDeltaTimeAsString($delta,$name,$id,&$js)//Uses $name, $id, and $js to make and append a JS line to make the countdown registered.
{
 //(Months are approximated as 30 days)
 //$months = $delta/60/60/24/30;
 //$monthsf = floor($months);
 $days = $delta/60/60/24;// - $monthsf*30;
 $daysf = floor($days);
 $hours = $delta/60/60 - $daysf*24;// - $monthsf*30*24;
 $hoursf = floor($hours);
 $minutes = $delta/60 - $daysf*24*60 - $hoursf*60;// - $monthsf*30*24*60;
 $minutesf = floor($minutes);
 $seconds = $delta - $daysf*24*60*60 - $hoursf*60*60 - $minutesf*60;// - $monthsf*30*24*60*60;
 
 //$monthsf.' months, '.
 
 //function addCountdown(name,id,days,hours,minutes,seconds)
 $js.=NL.'addCountdown("'.str_replace('"','\\"',$name).'","'.$id.'",'.$daysf.','.$hoursf.','.$minutesf.','.$seconds.');';
 
 return $daysf.' day'.($daysf==1?'':'s').', '.$hoursf.' hour'.($hoursf==1?'':'s').', '.$minutesf.' minute'.($minutesf==1?'':'s').', and '.$seconds.' second'.($seconds==1?'':'s');
}

function gadget_countdown($ownBox=true)
{//return "COUNTDOWN DISABLED";
 $start = time();
 
 $events = array(
  'Kickoff' => mktime(10,30,0,1,8,2011),
  'Robot Ship' => mktime(0,0,0,2,23,2011),
  'Greater Kansas City Regional' => mktime(0,0,0,3,10,2011),
  'Midwest Regional' => mktime(0,0,0,3,24,2011),
  'FIRST National Championship' => mktime(0,0,0,4,27,2011),
 );
 
 $ret = "";
 $js = "";
 $header = 2;
 foreach ($events as $event => $end)
 {
  $delta = $end-$start;
  if ($delta<0) {continue;}
  $line='<h'.$header.' id="timer'.md5($event).'">'.getDeltaTimeAsString($delta,$event,'timer'.md5($event),$js).' until '.$event.'!</h'.$header.'>';
  if ($event=='Robot Ship')//Special Condition
  {
   $totalDelta = $events['Robot Ship'] - $events['Kickoff'];
   $nowDelta = $events['Robot Ship'] - time();
   $percent = 1 - $nowDelta/$totalDelta;
   $ret.='<div class="progressbar" style="background-position:-'.(floor(_PROGRESSBAR_WIDTH*(1 - $percent))).'px 0px">'.$line.'</div>';
  }
  else
  {$ret.=$line;}
  $header++;
 }
 $ret.='<script type="text/javascript">'.$js.NL.'startCountdowns();'.NL.'</script>';
 return $ownBox?'<div class="tintedbox center">'.$ret.'</div>':$ret;
}
?>