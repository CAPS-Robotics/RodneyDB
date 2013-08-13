<?php
//Pathogen Collab (2410 Edition)
//Copyright © 2010 by Pathogen Studios
//http://www.pathogenstudios.com/
//
//===About Pathgogen Collab===
//Pathogen Collab is a minimalistic content management system indtended for team collaboration
//developed by "Pathogen" David Maas in 2010 for collaboration of projects by Pathogen Studios
//he also developed it with the intention that it would be used by the FIRST Robotics Team 2334
//(now merged with team 2410.) It has an emphasis of simplicity and flatness rather than general-
//ized customization. The theme is fixed and the plugin system is very basic. It also only works
//with one SQL engine (MySQL) as it uses it directly rather than abstracting it.
//
//===About this edition===
//This version was prepared for FRC Team 2410 prior to the 2011 FIRST season (December 2010)
//It was developed and maintained by David Maas, if you are having any issues with the system
//please do not hesitate to contact me. I can be reached at contact@pathogenstudios.com

require_once("System/system.php");

$eventTypes = array(
'commit'=>'view_more_text.png',
'bug'=>'error_fuck.png',
'taskadd'=>'add_small.png',
'assign'=>'contact_grey_add.png',
);

require_once("Modules/meetings.php");
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
else if (getPage()=="rss")
{
 define('DISABLE_PAGE_THEME',1);
 header("Content-Type: application/rss+xml");
 echo(gadget_recentActivity(true));
 die;
}
else if (getPage()=="checkin")
{checkinPage();}
else if (getPage()=="announce")
{makeAnnouncementPage();}
else
{homePage();}
?>
