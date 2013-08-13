<?php
//Pathogen Collab
//Copyright © 2010 by Pathogen Studios
//http://www.pathogenstudios.com/

require_once("System/rss.php");

/*! \brief Unifies infromation from many sources in the Pathogen Collab system and displays it here.

Currently tracks:
The RSS feeds of the code repos.
*/
function gadget_recentActivity($rss = false)
{
 global $eventTypes;
 $out = "";
 if ($rss)
 {
  $out.='<?xml version="1.0"?>'.NL.'<rss version="2.0">'.NL.'<channel>'.NL;
  $out.='<title>Pathogen Collab Recent Activity+ Feed</title>'.NL;
  $out.='<language>en-us</language>'.NL;
  $out.='<link>http';
  if ($_SERVER['HTTPS']=='on') {$out.='s';}
  $out.='://'.$_SERVER['HTTP_HOST'].'/</link>'.NL;
  $out.='<description>Pathogen Collab Recent Activity+ Feed</description>'.NL;
  $out.='<generator>Pathogen Collab</generator>'.NL.NL;
 }
 else
 {$out.= '<div class="tintedbox divlist"><h1>Recent Programming Activity</h1>';}
 $activity = array();
 
 //Read RSS from repos:
 global $_REPO_NAMES,$_REPO_NAMES_EXTENDED;
 if ($rss) {$repos=$_REPO_NAMES_EXTENDED;}else{$repos=$_REPO_NAMES;}
 
 foreach($repos as $repo)
 {
  if($rss)
  {$tags=array('title','guid','author','pubDate','description');}
  else
  {$tags=array('title','guid','author','pubDate');}
  $rss_array = loadRss(_REPO_PREFIX.$repo._REPO_SUFFIX,$tags);
  foreach($rss_array as $rssItem)
  {
   //Change the commit date back into unix time
   //This might not work with differently formatted dates.
   preg_match("/[A-Za-z]+, (\d{1,2}) (\w+) (\d{4}) (\d{1,2}):(\d{1,2}):(\d{1,2}) -\d+/",$rssItem['pubDate'],$matches);
   $timePosted = mktime($matches[4],$matches[5],$matches[6],getMonthNumberFromShortName($matches[2]),$matches[1],$matches[3]);
   while(!empty($activity[$timePosted])) {$timePosted++;}//Make sure we aren't overwriting another event.
   //Add the commit event.
   $activity[$timePosted]=array(
    'type'=>'commit',//$timePosted.' ::: '.$rssItem['pubDate'].' ::::: '.
    'description'=>$rssItem['author'].' commited code to '.$repo.
    ': '.($rss?'':'<a href="'.str_replace(_REPO_PREFIX,_REPO_PUBLIC_PREFIX,$rssItem['guid']).'">').$rssItem['title'].($rss?'':'</a>'),
    'rssItem'=>($rss?$rssItem:NULL),
    'link'=>str_replace(_REPO_PREFIX,_REPO_PUBLIC_PREFIX,$rssItem['guid']),
   );
  }
 }
 //End read RSS from repos.
 
 //If somehow Killian doesn't do anything for a long period of time, let people know.
 if (empty($activity)) {$data[]=array('type'=>'','description'=>'There has not been any recent activity.');}
 
 //"Render" the HTML
 krsort($activity);//This sorts the activity list by the key and inverted (Newest to oldest, the opposite would be ksort)
 
 if(!$rss)
 {
  $count=0;$max=10;$hardmax=$max+5;
  foreach ($activity as $actItem)
  {
   $count++;
   $icon = @$eventTypes[$actItem['type']];
   if (empty($icon)) {$icon="bullet_blue.png";}
   $out.='<div class="item" style="background-image:url(Content/icons/'.$icon.');';
   
   if ($count>$max)
   {
    if ($count>=$hardmax)
    {$out.="display:none;";}
    else
    {$out.="opacity:".(1-($count-$max)/($hardmax-$max)).";";}
   }
   
   $out.='">'.$actItem['description'].'</div>';
  }
 }
 else
 {
  foreach ($activity as $k => $actItem)
  {
   $icon = @$eventTypes[$actItem['type']];
   $out.='<item>'.NL;
   if ($actItem['rssItem']!==NULL)
   {
    $rssItem = $actItem['rssItem'];
    $out.='<title>'.htmlFilter($actItem['description']).'</title>'.NL;
    $out.='<guid isPermaLink="true">'.htmlFilter($actItem['link']).'</guid>'.NL;
    $out.='<description><![CDATA['.$rssItem['description'].']]></description>'.NL;//Need the CDATA stuff like in hg's rss feeds?
    $out.='<author>'.htmlFilter($rssItem['author']).'</author>'.NL;
    $out.='<pubDate>'.htmlFilter($rssItem['pubDate']).'</pubDate>'.NL;
   }
   else
   {
    $out.='<title>'.htmlFilter($actItem['description']).'</title>'.NL;
    $out.='<guid isPermaLink="true">'.htmlFilter($actItem['link']).'</guid>'.NL;
    $out.='<description>'.htmlFilter($actItem['description']).'</description>'.NL;//Need the CDATA stuff like in hg's rss feeds?
    $out.='<author>Pathogen Collab</author>'.NL;
    $out.='<pubDate>'.date('r',$k).'</pubDate>'.NL;
   }
   $out.='</item>'.NL.NL;
  }
 }
 
 if ($rss)
 {$out.='</channel>'.NL.'</rss>';}
 else
 {$out.='</div>';}
 
 return $out;
}

authIDRegisterService('<img src="Content/feedicons/RSS_32.png" alt="RSS">Recent Activities<sup>+</sup> RSS Feed','rss',
 'The Recent Activities<sup>+</sup> RSS Feed will deliver the activity feed to your reader without the need to log in.');
?>