<?php
//Rodney
//Copyright © 2013 by FIRST TEAM 2410
//http://www.mmr2410.com/

//Output buffering begin!
ob_start();
$pageTitle = "";

require_once("config.php");

//Utility Functions
function getMode() {return @$_POST['mode'];}
function getPage() {return @$_GET['p'];}
function getAction() {return @$_GET['a'];}
function setPage($newPage) {$_GET['p']=$newPage;}
function sqlFilter($data) {return mysql_real_escape_string($data);}
function htmlFilter($data) {return htmlspecialchars($data);}
function today() {return mktime(0,0,0);}
function tonight() {return mktime(23,59,59);}
if(_DEBUG)
{
 function sqlError($result,$sql="")
 {
  if (!$result)
  {message("MySQL Error:<br>".mysql_error().($sql&&_SUPERDEBUG?"<br><br>Query:<br>".htmlspecialchars($sql):""),1);return true;}else{return false;}
 }
}
else
{function sqlError($result,$sql="") {return !$result;}}

$_monthShortNames = array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7,"Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12);
function getMonthNumberFromShortName($month)
{
 global $_monthShortNames;
 if (empty($_monthShortNames[$month]))
 {message("FATAL: Unknown month '".$month."' please report this!",1);return 1;}
 else
 {return $_monthShortNames[$month];}
}

function formatPhoneNumber($ph) {return substr($ph,1,3)."-".substr($ph,4,3)."-".substr($ph,7,4);}//15551232410 to 555-123-2410
function unformatPhoneNumber($ph)//555-123-2410 (etc) to 15551232410 or NULL on unrecognized.
{
 if (preg_match('/\+?(\d{1,3})?[-.]?(\d{3})[-.]?(\d{3})[-.]?(\d{4})/',$ph,$matches))
 {
  if (empty($matches[1])) {$matches[1]="1";}
  return $matches[1].$matches[2].$matches[3].$matches[4];
 }
 else
 {return null;}
}

//Generic render functions
function message($message,$error=false,$centered=false)
{
 echo('<div class="'.($error?'redbox':'tintedbox').' floatbreaker'.($centered?' center':'').'">'.$message.'</div>');
}

$noNavBar = false;
function outputHeader($pageTitle="")
{
?>
<!DOCTYPE HTML>
<html>
<head>
 <meta http-equiv="content-type" content="text/html; charset=UTF-8">
 <title><?php echo("RodneyDB" . (empty($pageTitle)?"":" - ".$pageTitle)); ?></title>
 <link href="Content/style.css" rel="stylesheet" type="text/css">
 <script type="text/javascript" src="content/script.js"></script>
 <!--[if IE]>
 <link href="Content/iefixes.css" rel="stylesheet" type="text/css">
 <![endif]-->
</head>
<body>
 <a href="/" class="logo"></a>
<?php
 global $noNavBar;
 if (empty($noNavBar))
 {include("navBar.php");}
}

function outputFooter()
{
?>
 <div class="copyright">
  RodneyDB &copy; 2013 developed by <a href="http://www.pathogenstudios.com/">Pathogen Studios</a><br>
  Currently maintained by <a href="http://www.mmr2410.com/">FRC Team 2410</a><br>
  <a href="https://github.com/CAPS-Robotics/RodneyDB/">Github</a>
  <?php
  $network = "";
  switch($_SERVER['REMOTE_ADDR'])
  {
   case '76.92.202.42': $network = "D-NET";break;
   case '10.0.0.99': $network = "D-COM";break;
   case '12.187.58.129': $network = "Blue Valley High School";break;
   case '204.52.179.199': $network = "Center for Advanced Professional Studies";break;
  }
  if ($network!="") {echo("<br>Detected Location: ".$network);}
  ?>
 </div>
<?php
if (_SUPERDEBUG)
{
 echo('<div class="tintedbox center floatbreaker"><h3>Debug Info</h3></div>');
 $sdStyle = "width:250px;margin:10px;overflow:auto;min-height:500px;";
 echo('<div class="tintedbox floatleft" style="'.$sdStyle.'"><pre>$_SESSION = '.var_export($_SESSION,true).'</pre></div>');
 echo('<div class="tintedbox floatleft" style="'.$sdStyle.'"><pre>$_COOKIE = '.var_export($_COOKIE,true).'</pre></div>');
 echo('<div class="tintedbox floatleft" style="'.$sdStyle.'"><pre>$_GET = '.var_export($_GET,true).'</pre></div>');
 //Censor passwords in $_POST
 $protectedNames = array('password','pass','passkey','password1','password2','password_current');
 foreach($protectedNames as $name)
 {
  if (isset($_POST[$name]))
  {
   $_POST[$name] = str_repeat("*",strlen($_POST[$name]));
  }
 }
 echo('<div class="tintedbox floatleft" style="'.$sdStyle.'"><pre>$_POST = '.var_export($_POST,true).'</pre></div>');
 echo('<div class="tintedbox floatleft" style="'.$sdStyle.'"><pre>$_SERVER = '.var_export($_SERVER,true).'</pre></div>');
}
?>
</body>
</html>
<?php
}

function outputPage()
{
 global $pageTitle;
 $data = ob_get_clean();
 if (!defined('DISABLE_PAGE_THEME')) {outputHeader($pageTitle);}
 echo($data);
 if (!defined('DISABLE_PAGE_THEME')) {outputFooter();}
}
register_shutdown_function('outputPage');

//Session logic
$oldsessname = session_name();
session_name(_COOKIE_PREFIX.$oldsessname);
session_start();

//Database logic
$db = mysql_connect(_SQL_SERVER,_SQL_USERNAME,_SQL_PASSWORD);
mysql_select_db(_SQL_DATABASE);

//Define SQL Tables
define('USER_TABLE',_SQL_PREFIX.'users');
define('TASK_TABLE',_SQL_PREFIX.'tasks');

require_once("System/user.php");
enforceLoginLogic();
?>