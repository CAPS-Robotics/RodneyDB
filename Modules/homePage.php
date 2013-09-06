<?php
//Pathogen Collab
//Copyright © 2010 by Pathogen Studios
//http://www.pathogenstudios.com/

//! The home page
function homePage()
{
 global $currentUser;
 if (isLoggedIn()) {message('<h1 class="center">Welcome, '.$currentUser['name'].'</h1>'./*gadget_countdown(false)*/,0,1);}
 //echo(gadget_tasks());
 if ($currentUser['rank']<50)
 {
  message("Your account has not been validated, so you will not be able to use most of the features until it is.");
 }
 if (canViewRecentActivity($currentUser['rank']))
 {
  //echo(gadget_recentActivity());
 }
}
?>