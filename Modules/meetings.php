<?php
//Pathogen Collab (2410 Edition)
//Copyright © 2010 by Pathogen Studios
//http://www.pathogenstudios.com/

require_once("System/google.php");

$makeAnnouncementPageDiagLog = "";
function makeAnnouncementPage()
{
 global $currentUser;
 global $makeAnnouncementPageDiagLog;
 if (!canMakeAnnouncements($currentUser['rank'])) {message("You are not permitted to make announcements.");return;}
 
 if (getMode()=="announce")
 {
  //Getting Started...
  set_time_limit(0);
  $failure = 0;
  function diag($msg,$err=false)
  {
   global $makeAnnouncementPageDiagLog;
   $res="";
   if ($err) {$res .= '<span style="color:#D10000">';}
   $res.=$msg;
   if ($err) {$res .= '</span>';}
   $res.='<br>';
   $makeAnnouncementPageDiagLog.=$res;
   echo($res);
  }
  echo('<div class="tintedbox"><h1>Diagnostics Log:</h1>');
  diag("Preparing to send announcment...");
  diag("Getting list of members...");
  
  //Form Validation
  if (empty($_POST['subject']) || $_POST['subject']=="Subject") {$_POST['subject']="Metal Mustangs Announcement";}
  
  //Filter Setup
  $filter = "1";
  switch($_POST['filterPreset'])
  {
   case 1: $filter="`rank`>=50";break;
   case 2: $filter="`rank`>=60";break;
   case 3: $filter="`rank`>=90";break;
   case 4: $filter="`rank`=65";break;
   case 5: $filter="`rank`<50";break;
   case 6: $filter="`rank`>=50 AND `rank` <=60";break;
  }
  $useCustomFilter = (!empty($_POST['customFilter']) && $_POST['customFilter']!="Custom Filter");
  if ($useCustomFilter)
  {
   $filter = $_POST['customFilter'];
   diag("Using custom filter... (".$filter.")");
  }
  else
  {diag("Using filter preset ".$_POST['filterPreset']." (".$filter.")");}
  
  //Get Users
  $sql = "SELECT `name`,`email`,`phonenumber`,`phonetexting` FROM `".USER_TABLE."` WHERE ".$filter;
  diag("SQL Query:<br><pre>".$sql."</pre>");
  $res = mysql_query($sql);
  if (sqlError($res,$sql))
  {diag("There was an SQL error, the announcement will not continue.",1);$failure=1;}
  else
  {
   diag("There are ".mysql_num_rows($res)." people which will receive this announcment...");
   
   $bcc = '';
   $phoneNames = array();
   $phoneNumbers = array();
   $phCount = 0;
   while($data = mysql_fetch_assoc($res))
   {
    //Split into chunks of 5 phone numbers and make $to for sendMail
    $bcc.=$data['email'].', ';
    if ($data['phonetexting'])
    {
     $phoneNumbers[]=$data['phonenumber'];
     $phoneNames[]=$data['name'];
    }
   }
   //Inject some extra numbers:
   /*$phoneNumbers[]="19138326237";$phoneNames[]="Chris Cowan";
   $phoneNumbers[]="19135686416";$phoneNames[]="Person 92";
   $phoneNumbers[]="19136029217";$phoneNames[]="Garrett";
   $phoneNumbers[]="19136010017";$phoneNames[]="Jackson";
   $phoneNumbers[]="18162608874";$phoneNames[]="Tuckjer";*/
   
   //Send the email:
   if (!empty($_POST['email']))
   {
    diag("Preparing the send email...");
    $bcc = substr($bcc,0,-2);
    
    $to = 'Pathogen Collab <'._SYSTEM_EMAIL.'>';
    $subject = $_POST['subject'];
    $message = '<html><head><title>'.$subject.'</title></head><body>';
    $message.= '<p>'.$_POST['message'].'</p>';
    $message .= '</body></html>';
   
    $headers = 'MIME-Version: 1.0'."\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
    $headers .= 'Bcc: '.$bcc."\r\n";
    $headers .= 'From: '._SYSTEM_EMAIL."\r\n";
    
    diag("Sending email to Bcc '".$bcc."'");
    
    if (sendMail($to,$subject,$message,$headers)) {diag("Message sent successfully!");}else{diag("EMAIL WAS NOT SENT!",1);$failure+=0.5;}//Not a full failure, texts might still go through
   }else{diag("Skipping email announcement since it is disabled.");}
   
   //Send the texts:
   if (!empty($_POST['texting']))
   {
    diag("Preparing the texts...");
    $chunkedNumbers = array_chunk($phoneNumbers,5);
    $chunkedNames = array_chunk($phoneNames,5);
    
    diag("Logging in to Google...");
    if (google::login())
    {
     if (googleVoice::login())
     {
      diag("Sending texts!");
      foreach($chunkedNumbers as $kchunk => $chunk)
      {
       $numList = "";
       $nameList = "";
       foreach($chunk as $knum => $number)
       {
        $numList.=$number.",";
        $nameList.=$chunkedNames[$kchunk][$knum].", ";
       }
       
       $numList = substr($numList,0,-1);
       $nameList = substr($nameList,0,-2);
       
       diag("Sending text to ".$nameList." (".$numList.")");
       if (googleVoice::sendSMS($numList,$_POST['message']))
       {diag("Success!");}
       else
       {
        diag("FAILURE!",1);
        $failure+=0.01;//Increase the failure slightly, allows for many failed texts.
        diag("Try one more time...");
        sleep(2);
        if (googleVoice::sendSMS($numList,$_POST['message']))
        {diag("Second try worked!");$failure-=0.01;}else{diag("Second try failed!",1);}
       }
       sleep(6);
      }
     }else{diag("Failed to log into Google Voice!",1);$failure+=0.5;}
    }else{diag("Failed to log into Google!",1);$failure+=0.5;}
   }else{diag("Skipping text announcement since it is disabled.");}
   
   //diag("<pre>".var_export($chunkedNumbers,1)."</pre>");
  }
  echo("</div>");
  if ($failure>0 && $failure<1) {message("<h1>ANNOUNCMENT PARTIALLY FAILED!</h1>(See log for details, a report will be made)",1,1);}
  else if ($failure>0) {message("<h1>ANNOUNCMENT FAILED!</h1>(A report will be made)",1,1);}
  else {message("<h1>Announcment successfully sent!</h1>(A recipt will be emailed to you.)",0,1);}
  
  //Send message to admin:
  $to = _ADMIN_EMAIL.', '.$currentUser['email'];
  $subject = ($failure?"!!!Failed ":"").'Announcement Receipt';
  $message = '<html><head><title>'.$subject.'</title></head><body>';
  $message .= '<h1>Announcment Data:</h1>';
  if (!empty($_POST['texting'])) {$message.='<b>Sent as text</b><br>';}
  if (!empty($_POST['email'])) {$message.='<b>Sent as email</b><br>';}
  $message .= '<b>Filter Used:</b> "'.$filter.'" ('.($useCustomFilter?'Custom':'Preset #'.$_POST['filterPreset']).')<br>';
  $message .= '<b>Subject: </b>'.$_POST['subject'].'<br><b>Message:</b><br>'.$_POST['message'].'<br><br>';
  $message .= '<br><h2>Diagnostics Information:</h2>';
  $message .= 'POST Data:<br><pre>'.var_export($_POST,true).'</pre><br>';
  $message .= 'Diagnostics Log:<br>'.$makeAnnouncementPageDiagLog.'<br>';
  $message .= '</body></html>';
  
  $headers = 'MIME-Version: 1.0'."\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
  //$headers .= 'To: '.$to."\r\n";
  $headers .= 'From: '._SYSTEM_EMAIL."\r\n";
  
  if (!sendMail($to,$subject,$message,$headers)) {message("<h1>ERROR! Could not send announcement receipt!",1,1);}
 }
 else
 {
  ?>
  <form class="tintedbox" action="" method="post">
   <h1>Broadcast Announcment</h1>
   <input type="hidden" name="mode" value="announce">
   <b>Broadcast Options:</b><br>
   <input type="checkbox" name="texting" value="1" checked="checked"> <label for="texting">Send as Text</label><br>
   <input type="checkbox" name="email" value="1" checked="checked"> <label for="email">Send as Email</label><br>
   <label for="sendas">Send Email As:</label> <input type="text" name="sendas" value="rodney2410@pathogenstudios.com" style="width:250px;" id="mailSendAs">
    <a class="formnote" href="javascript:void(0)" onclick="document.getElementById('mailSendAs').value='<?php echo($currentUser['email']); ?>';">Send as me</a><br>
   <label for="filterPreset">Filter:</label> <select name="filterPreset" style="width:400px;">
    <option value="1" selected="selected">Team Members (&gt;=50)</option>
    <option value="6">Team Members with too few hours (&gt;=50 AND &lt;60)</option>
    <option value="2">Team Members++ (&gt;=60)</option>
    <option value="3">Team Leaders (&gt;=90)</option>
    <option value="4">Mentors (65)</option>
    <option value="5">Unconfirmed Team Members (&lt;50)</option>
    <option value="0">Use Custom Filter</option> UNIMPLEMENTED!
   </select><br>
   <span style="float:left;"><label for="customFilter">Custom Filter:</label> <input type="text" name="customFilter" value="Custom Filter" class="autoClear" onfocus="clearInputBox(this)"></span>
    <span style="float:left;font-size:0.7em;">(Don't use this unless you know what you are doing!)<br>(Use MySQL WHERE Syntax)</span><br>
   
   <br><b>Broadcast Content:</b><br>
   <label for="subject">Subject:</label> <input type="text" name="subject" value="Subject" class="autoClear" onfocus="clearInputBox(this)"><span class="formNote">(Email Only)</span><br>
   <label for="message">Message:</label><br>
   <textarea name="message" style="width:500px;height:200px;"></textarea><br>
   <br>
   <input type="submit" value="Broadcast" style="width:200px;height:40px;"><br>
   <small>If you have "Send as Text" enabled, it may take a long time to load.
   <?php if (_USE_MAIL_PROXY) {echo('<br>"Send as Email" may be slow because mail proxies are enabled by the administrator.');} ?></small>
  </form>
  <?php
 }
}
?>
