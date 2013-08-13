<?php
//Pathogen Collab
//Copyright © 2010 by Pathogen Studios
//http://www.pathogenstudios.com/

function directoryPage()
{
 global $currentUser;
 
 if (getAction()=="promote" && !empty($_GET['id']))
 {
  $sql = "UPDATE `".USER_TABLE."` SET `rank` = '50' WHERE `id`='".sqlFilter($_GET['id'])."'";
  $res = mysql_query($sql);
  sqlError($res,$sql);
  if ($res)
  {message("User rank promoted to 50 successfully.");}
  else
  {message("An error occoured whil promoting the user.",1);}
  
  $_GET['a']="2";//Go back to the team dir++
 }
 
 if (getAction()=="3" && canViewTeamDirectory2($currentUser['rank'])){
 	$_GET['a']="2";
 	
 	foreach($_POST as $key => $value){
 		$sql = "UPDATE `".USER_TABLE."` SET `hours`='".sqlFilter($value)."' WHERE `id`='".sqlFilter($key)."'";
 		$res = mysql_query($sql);
		sqlError($res,$sql);
 	}
 }
 
 if (getAction()=="shirtsizecounter" && canViewTeamDirectory2($currentUser['rank']))
 {
  echo('<div class="tintedbox center"><h1>Shirt Size Counter</h1><table><tr><th>Shirt Size</th><th>Count</th></tr>');
  $sql = "SELECT `shirtsize` FROM `".USER_TABLE."`";
  $res = mysql_query($sql);
  sqlError($res,$sql);
  
  $shirtSizes = array();
  while($data = mysql_fetch_assoc($res))
  {
   if (!isset($shirtSizes[$data['shirtsize']]))
   {$shirtSizes[$data['shirtsize']]=1;}
   else
   {$shirtSizes[$data['shirtsize']]++;}
  }
  
  foreach ($shirtSizes as $size => $count)
  {
   echo('<tr><td>'.$size.'</td><td>'.$count.'</td></tr>');
  }
  echo('</table></div>');
 }
 else
 {
  $extended=false;
  $vcard = false;
  if (getAction()=="2" && canViewTeamDirectory2($currentUser['rank'])) {$extended=true;}
  if (getAction()=="vcard" && canViewTeamDirectory2($currentUser['rank'])) {$extended=true;$vcard=true;}
  
  if (!$vcard)
  {
   echo('<div class="tintedbox center"><h1>Team Directory');
   if (canViewTeamDirectory2($currentUser['rank']))
   {
    if ($extended)
    {echo(' <span class="sectdetail"><a href="?p=directory">[Normal View]</a></span>');}
    else
    {echo(' <span class="sectdetail"><a href="?p=directory&a=2">[Extended View]</a></span>');}
   }
   echo('</h1><table><tr><th>Name');
   if ($extended) {echo('</th><th>');}
   echo('</th><th>Email Address</th><th>Phone Number</th>');
   if ($extended) {echo('<th>Home Phone</th><th>Shirt Size</th><th>Hours</th><form name="hours" action="/?p=directory&a=3" method="post">');}
   echo('</tr>');
  }
  
  $sql = "SELECT `id`,`name`,`email`,`phonenumber`,`phonetexting`,`shirtsize`,`homephone`,`hours`,`rank` FROM `".USER_TABLE."`";
  $res = mysql_query($sql);
  sqlError($res,$sql);
  
  while($data = mysql_fetch_assoc($res))
  {
   if (!$vcard)
   {echo('<tr><td>'.$data['name'].'</td>');}
   else
   {
    echo("BEGIN:VCARD".NL."VERSION:3.0".NL);
    echo("N:".$data['name'].NL);
    echo("FN:".$data['name'].NL);
   }
   if ($extended && !$vcard)
   {
    if ($data['rank']==25)
    {echo('<td><a href="/?p=directory&a=promote&id='.$data['id'].'">'.$data['rank'].'</a></td>');}
    else
    {echo('<td>'.$data['rank'].'</td>');}
   }
   if (!$vcard)
   {
    echo('<td>'.$data['email'].'</td>'.
    '<td>'.formatPhoneNumber($data['phonenumber']).($data['phonetexting']?"":'<span class="notxt">(TXT)</span>').'</td>');
    if ($extended) {echo('<td>'.(empty($data['homephone'])?"":formatPhoneNumber($data['homephone'])).'</td><td>'.$data['shirtsize'].'</td><td>'.'<input type"=text" name="'.$data['id'].'" value="'.$data['hours'].'">'.'</td>');}
    echo('</tr>');
   }
   else
   {
    echo('EMAIL;TYPE=PREF,INTERNET:'.$data['email'].NL);
    echo('TEL;TYPE=MOBILE,VOICE:'.formatPhoneNumber($data['phonenumber']).NL);
    echo('X-HAS-TEXTING:'.$data['phonetexting'].NL);
    if ($extended)
    {
     if (!empty($data['homephone'])) {echo('TEL;TYPE=HOME,VOICE:'.formatPhoneNumber($data['homephone']).NL);}
     echo('X-HOURS:'.$data['hours'].NL);
    }
    echo('END:VCARD'.NL.NL);
   }
  }
  if (!$vcard)
  {
   echo('</table>');
   if ($extended){ echo('<tr><td><input type="submit" value="Submit!" style="float:center"></td></tr>'); }
   
   if ($extended)
   {
    echo('<div class="tintedbox"></form><b>Utilities:</b> <a href="?p=directory&a=shirtsizecounter">Shirt Size Counter</a>, <a href="?p=directory&a=vcard">Download vCard Contact Data</a></div>');
   }
   
   echo('</div>');
  }
  else
  {
   header("Content-Type: text/x-vcard");
   define('DISABLE_PAGE_THEME',1);
  }
 }
}
?>
