<?php
//Rodney
//Copyright © 2013 by FIRST TEAM 2410
//http://www.mmr2410.com/

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
  if (getAction()=="2" && canViewTeamDirectory2($currentUser['rank'])) {$extended=true;}
  echo('<div class="tintedbox center"><h1>Team Directory++');
  if (canViewTeamDirectory2($currentUser['rank']))
  {
   /*if ($extended)
   {echo(' <span class="sectdetail"><a href="?p=directory">[Normal View]</a></span>');}
   else
   {echo(' <span class="sectdetail"><a href="?p=directory&a=2">[Extended View]</a></span>');}*/
  }
  echo('</h1><table>');
//<table><tr><th>Name');
//  if ($extended) {echo('</th><th>');}
  /*echo('</th>');
  if ($extended) {echo('<th>Home Phone</th><th>Shirt Size</th><th>Hours</th>');}
  echo('</tr>');*/
  
  $sql = "SELECT `id`,`name`,`email`,`phonenumber`,`phonetexting`,`shirtsize`,`homephone`,`hours`,`rank` FROM `".USER_TABLE."` WHERE `rank`>=60 ORDER BY `name`";
  $res = mysql_query($sql);
  sqlError($res,$sql);
  
  while($data = mysql_fetch_assoc($res))
  {
//<tr>
   echo('<tr><td>'.$data['name'].'</td>');
   /*if ($extended)
   {
    if ($data['rank']==25)
    {echo('<td><a href="/?p=directory&a=promote&id='.$data['id'].'">'.$data['rank'].'</a></td>');}
    else
    {echo('<td>'.$data['rank'].'</td>');}
   }
   echo('<td>'.$data['email'].'</td>'.
   '<td>'.formatPhoneNumber($data['phonenumber']).($data['phonetexting']?"":'<span class="notxt">(TXT)</span>').'</td>');
   if ($extended) {echo('<td>'.(empty($data['homephone'])?"":formatPhoneNumber($data['homephone'])).'</td><td>'.$data['shirtsize'].'</td><td>'.$data['hours'].'</td>');}
*/
   echo('</tr>');
  }
  echo('</table>');
  
  if ($extended)
  {
   echo('<div class="tintedbox"><b>Utilities:</b> <a href="?p=directory&a=shirtsizecounter">Shirt Size Counter</a></div>');
  }
  
  echo('</div>');
 }
}
?>