<?php
//Pathogen Collab (2410 Edition)
//Copyright © 2010 by Pathogen Studios
//http://www.pathogenstudios.com/
global $currentUser;
$enableLinks = (_REQUIRE_LOGIN && isLoggedIn()) || !_REQUIRE_LOGIN;
?>
<div class="tintedbox navbar">
 <a href="/">Home</a>
 <?php
 if($enableLinks)
 {
  if (canViewTeamDirectory($currentUser['rank'])) {echo('<a href="?p=directory">Team Directory</a>');}
  if (canMakeAnnouncements($currentUser['rank'])) {echo('<a href="/?p=announce">Broadcast Announcement</a>');}
  if (canStartCheckin($currentUser['rank'])) {echo('<a href="/?p=checkin">Checkin</a>');}
 }
 ?>
 <span>
  <?php
  //Right-side of navbar:
  if (isLoggedIn())
  {
   ?>
   <a href="/?p=logout">Logout</a>
   <a href="/?p=account">Your Account</a>
   <?php
  }
  else
  {echo('<a href="/?p=register">Register</a>');}
  ?>
 </span>
 <div class="floatbreaker"></div>
</div>
