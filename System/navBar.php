<?php
//Rodney
//Copyright © 2013 by FIRST TEAM 2410
//http://www.mmr2410.com/
global $currentUser;
$enableLinks = (_REQUIRE_LOGIN && isLoggedIn()) || !_REQUIRE_LOGIN;
?>
<div class="tintedbox navbar">
 <a href="index.php">Home</a>
 <?php
 if($enableLinks)
 {
  if (canViewTeamDirectory($currentUser['rank'])) {echo('<a href="?p=directory">Team Directory</a>');}
  if (canStartCheckin($currentUser['rank'])) {echo('<a href="?p=checkin">Checkin</a>');}
 }
 ?>
 <span>
  <?php
  //Right-side of navbar:
  if (isLoggedIn())
  {
   ?>
   <a href="?p=logout">Logout</a>
   <a href="?p=account">Your Account</a>
   <?php
  }
  else
  {echo('<a href="?p=register">Register</a>');}
  ?>
 </span>
 <div class="floatbreaker"></div>
</div>
