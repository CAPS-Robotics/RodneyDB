<?php
//Rodney
//Copyright © 2013 by FIRST TEAM 2410
//http://www.mmr2410.com/

define('_REPO_USER','dbo430924922');
define('_REPO_PASS','1nn0c3nts_Twa1n');
define('_REPO_METHOD','http');
define('_REPO_SERVER','db430924922.db.1and1.com');
define('_REPO_PORT','3306');

define('_REPO_PUBLIC_METHOD','https');
define('_REPO_PUBLIC_SERVER',substr($_SERVER['HTTP_HOST'],0,strpos($_SERVER['HTTP_HOST'],":")));
define('_REPO_PUBLIC_PORT','2410');

$_REPO_NAMES = array("FRC2011",);
$_REPO_NAMES_EXTENDED = array_merge($_REPO_NAMES,array());

//Don't edit this:
define('_REPO_PREFIX',_REPO_METHOD.'://'.(_REPO_USER?(_REPO_USER.(_REPO_PASS?(":"._REPO_PASS):'').'@'):'')._REPO_SERVER.(_REPO_PORT?(':'._REPO_PORT):'').'/');
define('_REPO_SUFFIX','/rss-log');
define('_REPO_PUBLIC_PREFIX',_REPO_PUBLIC_METHOD.'://'.(_REPO_PUBLIC_SERVER?_REPO_PUBLIC_SERVER:_REPO_SERVER).(_REPO_PUBLIC_PORT?(':'._REPO_PUBLIC_PORT):'').'/');
?>