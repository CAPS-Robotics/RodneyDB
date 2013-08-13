<?php
//Pathogen Collab
//Copyright © 2010 by Pathogen Studios
//http://www.pathogenstudios.com/

//Utility functions
function isLoggedIn() {return @$_SESSION['loggedin']==true;}
function getShortUsername($name) {return str_replace(_USERNAME_PREFIX,"",$name);}
function encryptPassword($password) {return md5($password);}
function getRankName($rank)
{
 global $definedRanks,$specialRanks;
 if (isset($specialRanks[$rank])) {return $specialRanks[$rank];}//Ranks that are assigned in special conditions (EG: Not a >= type)
 $i = $rank;
 if ($i<0) {return "Banned / Deactivated";}
 while (!isset($definedRanks[$i])) {$i--;}
 return $definedRanks[$i];
}

//Pages
function loginScreen($message="Please enter your login details below.")
{
?>
<div class="tintedbox center">
<div style="margin-bottom:10px;"><?php echo($message); ?></div>
<form method="post" action="">
<input type="hidden" name="mode" value="login">
<input type="text" name="user" value="Username/Email" class="autoClear" onfocus="clearInputBox(this)">&nbsp;
<input type="password" name="pass" value="Password" class="autoClear" onfocus="clearInputBox(this)"><br><br>
<input type="submit" value="Login">
</form>
</div>
<?php
}

function registerScreen()
{
 if (!_ALLOW_REGISTRATION) {message("Registration is disabled.",1);}
 
 //Defaults
 $usernameDefault = "Your Name";
 $passwordDefault = "Password";
 $phoneDefault = "Mobile Phone Number";
 $phoneDefault2 = "Home Phone Number";
 $emailDefault = "Email Address";
 $shirtSizeDefault = "L";
 $shirtSizes = array("S","M","L","XL","XXL","Other");
 $idDefault = "Student ID";
 
 if (empty($_POST['user'])) {$_POST['user']=$usernameDefault;}
 if (empty($_POST['password1'])) {$_POST['password1']=$passwordDefault;}
 if (empty($_POST['password2'])) {$_POST['password2']=$passwordDefault;}
 if (empty($_POST['email'])) {$_POST['email']=$emailDefault;}
 if (empty($_POST['phone'])) {$_POST['phone']=$phoneDefault;}
 if (empty($_POST['phone2'])) {$_POST['phone2']=$phoneDefault2;}
 if (empty($_POST['texting'])) {$_POST['texting']="1";}
 if (empty($_POST['shirtsize'])) {$_POST['shirtsize']=$shirtSizeDefault;}
 if (empty($_POST['stuid'])) {$_POST['stuid']=$idDefault;}
 
 $usernameInput = $_POST['user'];
 $passwordInput = $_POST['password1'];
 $emailInput = $_POST['email'];
 $phoneInput = $_POST['phone'];
 $phoneInput2 = $_POST['phone2'];
 $textingInput = $_POST['texting']=="0"?false:true;//Assume yes
 $shirtSizeInput = $_POST['shirtsize'];
 $idInput = $_POST['stuid'];
 
 //Validation
 $failure = false;
 $failMsg = "";
 $showForm = true;
 //Specific inputs:
 $failUsername = false;
 //$failPassword = (Password always fails if anything else does.)
 $failEmail = false;
 $failPhone = false;
 $failPhone2 = false;
 $failId = false;
 if (getMode() == "register")
 {
  //Validate input requirements
  if ($usernameInput==$usernameDefault) {$failure=true;$failMsg.='Please enter your name.<br>';$failUsername = true;}
  if ($passwordInput==$passwordDefault) {$failure=true;$failMsg.='Please enter a password.<br>';}
  if ($passwordInput!=$_POST['password2']) {$failure=true;$failMsg.='Passwords did not match.<br>';}
  if ($emailInput==$emailDefault) {$failure=true;$failMsg.='Please enter your email address.<br>';$failEmail = true;}
  if ($phoneInput==$phoneDefault) {$failure=true;$failMsg.='Please enter your phone number.<br>';$failPhone = true;}
  else if (unformatPhoneNumber($phoneInput)===null) {$failure=true;$failMsg.='Please use a valid mobile phone number format such as 913-555-2410.<br>';$failPhone=true;}
  if (unformatPhoneNumber($phoneInput2)===null && $phoneInput2!=$phoneDefault2 && $phoneInput2!="") {$failure=true;$failMsg.='Please use a valid home phone number format such as 913-555-2410.<br>';$failPhone2=true;}//Optional, do not validate default.
  if ($idInput==$idDefault) {$failure=true;$failMsg.='Please enter your Student ID';$failId = true;}
  
  //Database validation
  if (!$failUsername)
  {
   $usernameFiltered = sqlFilter($usernameInput);
   $sql = "SELECT `id` FROM `".USER_TABLE."` WHERE (`name`='".$usernameFiltered."' OR `name`='"._USERNAME_PREFIX.$usernameFiltered."')";
   $res = mysql_query($sql);
   sqlError($res,$sql);
   if (mysql_num_rows($res)!=0)
   {
    $failure=true;
    $failUsername=true;
    $failMsg.='That name is already in use.<br>';
   }
  }
  
  if (!$failEmail)
  {
   $sql = "SELECT `id` FROM `".USER_TABLE."` WHERE `email`='".sqlFilter($emailInput)."'";
   $res = mysql_query($sql);
   sqlError($res,$sql);
   if (mysql_num_rows($res)!=0)
   {
    $failure=true;
    $failEmail=true;
    $failMsg.='That email is already in use.<br>';
   }
  }
  
  if (!$failure)//Do registration!
  {
   $showForm = false;
   
   if ($phoneInput2==$phoneDefault2) {$phoneInput2="";}//Optional
   else if (!empty($phoneInput2)) {$phoneInput2 = unformatPhoneNumber($phoneInput2);}
   $phoneInput = unformatPhoneNumber($phoneInput);
   
   $sql = "INSERT INTO ".USER_TABLE." (`id`, `name`, `rank`, `password`, `email`, `authid`, `phonenumber`, `phonetexting`,`homephone`,`shirtsize`,`checkinid`) VALUES".
   " (NULL, '".sqlFilter($usernameInput)."', '"._DEFAULT_USER_RANK."', '".sqlFilter(md5($passwordInput))."', ".
    "'".sqlFilter($emailInput)."', '', '".sqlFilter($phoneInput)."', '".($textingInput?"1":"0")."','".sqlFilter($phoneInput2)."','".sqlFilter($shirtSizeInput)."','".
    sqlFilter($idInput)."');";
   $res = mysql_query($sql);
   if (!sqlError($res,$sql))
   {
    //Try to log in...
    if (tryLogin($usernameInput,$passwordInput)===true)
    {message('You are now registered and have been automatically logged in!<br><br><a href="/">Click here to go to the home page</a>');}
    else
    {message('You are now registered, but there was an issue logging you in.');}//No idea why this would ever happen but might as well handle the case.
   }
   else
   {message('There was an issue with your registration.');}
  }
 }
 
 //This is a small utility function to make the form code cleaner.
 function formHelper($fail,$in,$def)
 {
  echo('value="'.$in.'"  class="');
  if ($in==$def) {echo('autoClear');}
  if ($in==$def && $fail) {echo(' ');}
  if ($fail) {echo("failed");}
  echo('"');
  if ($in==$def) {echo(' onfocus="clearInputBox(this)"');}
 }
 
 if ($failMsg!="") {message($failMsg,true);}
 if ($showForm)
 {
 ?>
 <div class="tintedbox">
 <form method="post" action="">
  <h1>Team 2410 Registration <span class="sectwarn">All boxes are required.</span></h1>
  <input type="hidden" name="mode" value="register">
  <h2>Login Information:</h2>
  <input type="text" name="user" <?php formHelper($failUsername,$usernameInput,$usernameDefault) ?>><span class="formNote">(Please use your real name.)</span><br>
  <input type="password" name="password1" value="Password" class="autoClear<?php if ($failure) {echo(" failed");} ?>" onfocus="clearInputBox(this)"><span class="formNote">(Enter your password.)</span><br>
  <input type="password" name="password2" value="Password" class="autoClear<?php if ($failure) {echo(" failed");} ?>" onfocus="clearInputBox(this)"><span class="formNote">(Confirm your password.)</span><br>
  <h2>Contact Information:</h2>
  <input type="text" name="email" <?php formHelper($failEmail,$emailInput,$emailDefault) ?>><span class="formNote">(Use one you will check.)</span><br>
  <input type="text" name="phone" <?php formHelper($failPhone,$phoneInput,$phoneDefault) ?>><span class="formNote">(Or primary phone.)</span><br>
  <label for="texting">Support texting?</label> <select name="texting">
   <option value="1"<?php if ($textingInput) {echo(' selected="selected"');} ?>>Yes</option>
   <option value="0"<?php if (!$textingInput) {echo(' selected="selected"');} ?>>No</option>
  </select><br>
  <input type="text" name="phone2" <?php formHelper($failPhone2,$phoneInput2,$phoneDefault2) ?>><span class="formNote">(Optional.)</span><br>
  <h2>Misc:</h2>
  <label for="shirtsize">Shirt Size:</label> <select name="shirtsize" style="width:100px">
  <?php
  foreach($shirtSizes as $size)
  {
   echo('<option value="'.$size.'"'.($shirtSizeInput==$size?' selected="selected"':'').'>'.$size.'</option>');
  }
  ?>
  </select><br>
  <input type="text" name="stuid"<?php formHelper($failId,$idInput,$idDefault) ?>><br>
  <br>
  <input type="submit" value="Register">
 </form>
 </div>
 <?php
 }//End of showForm
}

//Gets user info!
$_USER_DATA_CACHE = array();
function getUser($id=NULL)
{
 global $_USER_DATA_CACHE;
 if ($id===NULL) {$id=$_SESSION['loginid'];}
 if (empty($id) && $id!==0) {return NULL;}
 if (!empty($_USER_DATA_CACHE[$id])) {return $_USER_DATA_CACHE[$id];}
 if ($id===0)
 {
  return ($_USER_DATA_CACHE[0] = array(
   'id'=>0,
   'name'=>"Guest",
   'rank'=>0,
   'password'=>"",
   'email'=>"",
   'authid'=>"",
   'phonenumber'=>"",
   'phonetexting'=>0,
   'homephone'=>"",
   'shirtsize'=>""
  ));
 }
 
 $sql = "SELECT * FROM `".USER_TABLE."` WHERE `id`='".sqlFilter($id)."' LIMIT 1";
 $res = mysql_query($sql);
 if (sqlError($res,$sql) || mysql_num_rows($res)!=1)
 {return NULL;}
 else
 {return ($_USER_DATA_CACHE[$id] = mysql_fetch_assoc($res));}
}

//Current User Stuff
$currentUser = array();
function refreshCurrentUserData()
{
 global $currentUser;
 if (isLoggedIn())
 {$currentUser = getUser($_SESSION['loginid']);}//Note; this was added in the 2410 version so older code won't be using it.
 else
 {
  $_SESSION['loginid'] = 0;
  $_SESSION['loginrank'] = 0;
  $_SESSION['restrictpage'] = "";
  $currentUser = getUser(0);
 }
}
refreshCurrentUserData();

//Login Stuff
function tryLogin($username,$password)
{
 $username = sqlFilter($username);
 $password = sqlFilter(md5($password));
 $sql = "SELECT `id`,`rank` FROM `".USER_TABLE."`".
  " WHERE ((`name`='".$username."' OR `name`='"._USERNAME_PREFIX.$username."' OR `email`='".$username."') AND `password`='".$password."') OR ".
  "(`authid`='".$username."' AND `authid`!='')";
 $res = mysql_query($sql);
 sqlError($res,$sql);
 if (mysql_num_rows($res)<1) {return "Unknown username/password combo";}
 else if (mysql_num_rows($res)>1) {return "A fatal login error has occured. Try using your email address instead of your username.";}
 $data = mysql_fetch_assoc($res);
 $_SESSION['loggedin']=true;
 $_SESSION['loginid']=$data['id'];
 $_SESSION['loginrank']=$data['rank'];
 $_SESSION['restrictpage']="";
 refreshCurrentUserData();
 return true;
}

//AuthID Stuff
$authIDServices = array();
function authIDRegisterService($serviceTitle,$servicePage,$serviceDescription="")
{
 global $authIDServices;
 $authIDServices[]=array(
  'title'=>$serviceTitle,
  'p'=>$servicePage,
  'desc'=>$serviceDescription
 );
}

//! User account info and control panel
function accountPage()
{
 if (!isLoggedIn())
 {
  message("You must be logged in to view this page.",1);
  homePage();
  return;
 }
 //Change password
 if (getMode()=="chpassword")
 {
  $chpassFail = false;
  if (empty($_POST['password1']) || empty($_POST['password2']) || empty($_POST['password_current'])) {$chpassFail = true;}
  if ($_POST['password1']=="Password" || $_POST['password2']=="Password" || $_POST['password_current']=="Password") {$chpassFail = true;}
  
  if ($chpassFail)
  {message("Please eneter a valid password.",1);}
  else
  {
   $sql = "SELECT `id` FROM `".USER_TABLE."` WHERE `id`='".sqlFilter($_SESSION['loginid'])."' AND `password`='".sqlFilter(md5($_POST['password_current']))."'";
   $res = mysql_query($sql);
   
   if (sqlError($res,$sql))
   {message("There was an issue validating your current password.",1);}
   else
   {
    if (mysql_num_rows($res)<1)
    {message("Current password was not correct.",1);}
    else
    {
     $sql = "UPDATE `".USER_TABLE."` SET `password` = '".sqlFilter(md5($_POST['password1']))."' WHERE `id`='".sqlFilter($_SESSION['loginid'])."'";
     $res = mysql_query($sql);
     if (sqlError($res,$sql))
     {message("There was an error while changing your password.",1);}
     else
     {message("Password changed successfully.");}
    }
   }
  }
 }
 //Generate a new AuthID
 else if (getAction()=="newauthid")
 {
  $newcode = "";
  for($i=0;$i<20;$i++) {$newcode.=chr(rand(32,126));}
  $newcode = substr(md5($newcode),1,10);
  
  $sql = "UPDATE `".USER_TABLE."` SET `authid` = '".sqlFilter($newcode)."' WHERE `id`='".sqlFilter($_SESSION['loginid'])."'";
  $res = mysql_query($sql);
  if (sqlError($res,$sql))
  {message("Your new AuthID was not generated successfully.",1);}
  else
  {message("Your new AuthID was generated.");}
 }
 else if (getMode()=="updateinfo")
 {
  message("Unimplemented",1);
 }
 
 //The actual page
 $data = getUser();
 ?>
 <div class="tintedbox">
  <h1><?php echo(htmlFilter($data['name'])); ?></h1>
  <h2>Your Information<!-- <span><a href="javascript:void(0);" onclick="swapOnce('info1','info2',this);">[Edit]</a></span>--></h2>
  <form class="tintedbox" method="post">
   <input type="hidden" name="mode" value="updateinfo">
   <div class="floatleft" style="text-align:right;margin-right:10px;">
    Email Address:<br>
    Mobile Phone:<br>
    Home Phone:<br>
    Shirt Size:<br>
    Rank:<br>
    Hours:
   </div>
   <div class="floatleft" id="info1">
    <?php
    $info = getUser();
    echo($info['email']."<br>");
    echo(formatPhoneNumber($info['phonenumber'])." ".($info['phonetexting']?"(With texting)":"(Without texting)")."<br>");
    echo((empty($info['homephone'])?"None":formatPhoneNumber($info['homephone']))."<br>");
    echo($info['shirtsize']."<br>");
    echo(getRankName($info['rank'])." (".$info['rank'].")<br>");
    echo($info['hours']."<br>");
    ?>
   </div>
   <div class="floatleft" id="info2" style="display:none;">
    <?php
    $info = getUser();
    echo('<input name="email" value="'.$info['email'].'"><br>');
    echo('<input name="phonenumber" value="'.$info['phonenumber'].'"> <select name="phonetexting">'.
     '<option value="1"'.($info['phonetexting']?'selected="selected"':'').'>(With texting)</option>'.
     '<option value="0"'.($info['phonetexting']?'':'selected="selected"').'>(Without texting)</option>'.
     '</select><br>');
    echo('<input name="phonenumber2" value="'.$info['homephone'].'">');
    echo('<input name="shirtsize" value="'.$info['shirtsize'].'"><br>');
    echo(getRankName($info['rank'])." (".$info['rank'].")<br>");
    echo($info['hours']."<br>");
    ?>
    <input type="submit" value="Apply Changes">
   </div>
   <div class="floatbreaker"></div>
  </form>
  <h2>Change Password</h2>
  <form class="tintedbox" method="post">
   <input type="hidden" name="mode" value="chpassword">
   <div class="floatleft" style="text-align:right;margin-right:10px;">
    New Password:<br>
    Confirm:<br>
    Current Password:
   </div>
   <div class="floatleft">
    <input type="password" name="password1" value="Password" class="autoClear" onfocus="clearInputBox(this)"><br>
    <input type="password" name="password2" value="Password" class="autoClear" onfocus="clearInputBox(this)"><br>
    <input type="password" name="password_current" value="Password" class="autoClear" onfocus="clearInputBox(this)"><br>
    <input type="submit" value="Change Password">
   </div>
   <div class="floatbreaker"></div>
  </form><br>
  <h2>AuthID</h2>
  <div class="tintedbox center">
   <div class="explain">Your AuthID can be used to log in without using your username and password. It is useful for automatically authenticating for
   things like the recent activity RSS feed.</div>
   <div style="text-align:center;font-size:1.5em;"><?php echo(empty($data['authid'])?"You do not currently have a AuthID.":htmlFilter($data['authid'])); ?></div>
   <a href="?p=account&a=newauthid">Click here to generate a new AuthID</a><br>
   <span class="disclaimer">Warning! This will break anything using your old AuthID!</span>
  </div><br>
  <h2>AuthID Services</h2>
  <div class="tintedbox center">
   <?php
   $authIDOn = true;
   if (empty($data['authid']))
   {
    echo("You need an AuthID in order to use AuthID services, please generate one above.");
    $authIDOn = false;
   }
   //else
   
   global $authIDServices;
   if (empty($authIDServices))
   {
    echo("There are no AuthID services available right now.");
   }
   else
   {
    foreach ($authIDServices as $service)
    {
     echo("<h3>");
     if ($authIDOn) {echo('<a href="/?p='.$service['p'].'&auth='.htmlFilter(urlencode($data['authid'])).'">');}
     echo($service['title']);
     if ($authIDOn) {echo('</a>');}
     echo('</h3>'.$service['desc']);
    }
   }
   ?>
  </div>
 </div>
 <?php
}

function logout()
{
 session_unset();
 unset($_COOKIE[session_name()]);
}

function enforceLoginLogic()
{
 //Auth GET login
 if (!empty($_GET['auth']) && !isLoggedIn())
 {
  if (!tryLogin($_GET['auth'],""))
  {
   header("HTTP/1.0 403 Forbidden");
   define('DISABLE_PAGE_THEME',1);
   die;
  }
 }
 
 //User login logic
 if (getPage()=='login')
 {
  if (!isLoggedIn())
  {
   loginScreen();
   die;
  }
  else
  {
   setPage("");
   message('You are already logged in! <a href="?p=logout">Want to log out?</a>');
  }
 }
 else if (getPage()=='logout')
 {
  //unset($_SESSION);
  logout();
  message('You have been logged out. <a href="?">Click here to go home.</a>');
  die;
 }
 else if (getPage()=='register')
 {
  registerScreen();
  die;
 }
 else if (!isLoggedIn() && getMode()=="login")
 {
  $msg = tryLogin($_POST['user'],$_POST['pass']);
  if ($msg===true)
  {message("You have been logged in.");}
  else
  {
   message($msg,true);
   loginScreen();
   die;
  }
 }
 else if (_REQUIRE_LOGIN && !isLoggedIn())
 {
  loginScreen("Login is required for this website.");
  die;
 }
}
?>