<?php
//Rodney
//Copyright © 2013 by FIRST TEAM 2410
//http://www.mmr2410.com/
//
//===About this edition===
//This version was prepared for FRC Team 2410 prior to the 2011 FIRST season (December 2010)
//It was developed by David Maas, contact@pathogenstudios.com
//If you are having any issues with the system you should contact the current maintenance crew
//Eric Osburn, bjonesen@gmail.com, Thomas Gorham, tgiv014@gmail.com, and Tony Ma, tuogex@yahoo.com. 

require_once("System/system.php");

$eventTypes = array(
'commit'=>'view_more_text.png',
'bug'=>'error_fuck.png',
'taskadd'=>'add_small.png',
'assign'=>'contact_grey_add.png',
);

//require_once("Modules/meetings.php");
require_once("Modules/homePage.php");
require_once("Modules/directory.php");
require_once("Modules/checkin.php");

//Restrict Page
if (!empty($_SESSION['restrictpage']) && getPage()!=$_SESSION['restrictpage'])
{
 $rpage = $_SESSION['restrictpage'];
 logout();
 message("This page is restricted, you have been logged out.",1);
 setPage($rpage);
 die;
}

//Page choosing logic
if (getPage()=="account")
{accountPage();}
else if (getPage()=="directory")
{directoryPage();}
/*else if (getPage()=="rss")
{
 define('DISABLE_PAGE_THEME',1);
 header("Content-Type: application/rss+xml");
 echo(gadget_recentActivity(true));
 die;
}*/
else if (getPage()=="checkin")
{checkinPage();}
/*else if (getPage()=="announce")
{makeAnnouncementPage();}*/
else
{homePage();}
?>
