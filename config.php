<?php
//Rodney
//Copyright © 2013 by FIRST TEAM 2410
//http://www.mmr2410.com/

define('_SQL_SERVER','db430924922.db.1and1.com:3306');
define('_SQL_USERNAME','dbo430924922');
define('_SQL_PASSWORD','1nn0c3nts_Twa1n');
define('_SQL_DATABASE','db430924922');
define('_SQL_PREFIX','');

define('_COOKIE_PREFIX','pc_');

define('_USERNAME_PREFIX',"Pathogen ");//An optional prefix to certain usernames. //This is not actually used by the 2410 website, but it allows my internal name to be "Pathogen David Maas" and log in as "David Maas"
define('_DEFAULT_USER_RANK',25);//25 - Unconfirmed users -- This is the default when registered, not guest. Guests are assumed to be 0. //See permissions config for more details.
define('_REQUIRE_LOGIN',true);
define('_ALLOW_REGISTRATION',true);
define('_DEBUG',substr_count($_SERVER['HTTP_HOST'],"d-happy")>=1?true:false);//The condition makes it so _DEBUG is on when I am in my home network. //Change "d-happy" to the internal hostname of your server.
define('_SUPERDEBUG',_DEBUG);
define('_ADMIN_EMAIL','rodney@mmr2410.com');//I would appreciate if you set up a forward email in the 2410 1&1 admin panel so I still get admin messages so I can track RODNEY's usage and success rates.
define('_SYSTEM_EMAIL','rodney2410@pathogenstudios.com');

include_once("Config/repo.php");

include_once("Config/google.php");

define('_LONG_DATE','F j, Y');
define('_LONG_DATE_TIME','F j, Y \a\t g:i');
define('_SHORT_DATE','M j, Y');

define('NL',"\n");

//Checkin Rules
define('CHECKIN_MAX',10);//Maximum hours before they are denied.

//Mail Settings //If you can get sendmail working on your network, then all power to you. If not, you can keep the mail proxy there but don't abuse it.
define('_USE_MAIL_PROXY',1);//Slower but less of a hassle -- using this until a better method is available.
define('_MAIL_PROXY','http://www.pathogenstudios.com/mp/mailProxy.php');
define('_MAIL_PROXY_AUTH','drat@a?7nA4afed!a_a+ubrut8udrax_z#che?3ud&uWus*@freQu_!echa!Hafu');

//Permission logic
require_once("Config/permissions.php");

//Style constants
define('_PROGRESSBAR_WIDTH',800);
?>
