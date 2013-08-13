<?php
//Pathogen Collab
//Copyright © 2010 by Pathogen Studios
//http://www.pathogenstudios.com/

//Derived from http://www.pixel2life.com/forums/index.php?showtopic=35884
//$_rss_tags = array('title','link','guid','comments','description','pubDate','category');
$_rss_tags = array('title','link','guid','comments','description','pubDate','category');
function loadRss($url,$rss_tags=array())
{
 global $_rss_tags;
 if (empty($rss_tags)) {$rss_tags = $_rss_tags;}
 $doc = new DOMdocument();
 $doc->load($url);
 $rss_array = array();
 $items = array();
 foreach($doc->getElementsByTagName('item') as $node)
 {
  foreach ($rss_tags as $k=>$v)
  {
   $item = $node->getElementsByTagName($v)->item(0);
   if ($item!=NULL) {$items[$v] = $item->nodeValue;}
  }
  array_push($rss_array,$items);
 }
 return $rss_array;
}
?>