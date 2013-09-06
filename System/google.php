<?php
//Rodney
//Copyright © 2013 by FIRST TEAM 2410
//http://www.mmr2410.com/

require_once("System/class.xhttp.php");

class google
{
 public static $authCode = null;
 public static function login()
 {
  $data = array();
  $data['post'] = array(
   'accountType' => 'GOOGLE',
   'Email'       => _GOOGLE_USER,
   'Passwd'      => _GOOGLE_PASS,
   'service'     => 'grandcentral',
   'source'      => 'pathogenstudios-pathogencollab-1.0'
  );
  self::fixSSL($data);
  
  $response = xhttp::fetch('https://www.google.com/accounts/ClientLogin',$data);
  
  if (!$response['successful'])
  {
   if (_DEBUG) {message('Google Login Error!<br><pre>$data = '.str_replace(_GOOGLE_PASS,"**CENSORED**",var_export($data,true)).'------------'.str_replace(_GOOGLE_PASS,"**CENSORED**",var_export($response,true)).'</pre>',true);}
   return false;
  }
  
  preg_match('/Auth=(.+)/', $response['body'], $matches);
  self::$authCode = $matches[1];
  
  return true;
 }
 
 public static function fixSSL(&$data) {if (_GOOGLE_FIX_SSL) {$data['ssl-verifypeer']=false;}}
 public static function authenticate(&$data) {if (self::isAuthenticated()) {$data['headers'] = array('Authorization' => 'GoogleLogin auth='.self::$authCode);}}
 public static function isAuthenticated() {return self::$authCode!==null;}
}

class googleVoice
{
 public static $rnrse = "";//Like an API code but unofficial
 public static function login()
 {
  if (!google::isAuthenticated()) {return false;}
  $data = array();
  google::authenticate($data);
  google::fixSSL($data);
  
  $response = xhttp::fetch('https://www.google.com/voice',$data);
  
  if (!$response['successful'])
  {
   if (_DEBUG) {message('Google Voice Login Error!<br><pre>$data = '.var_export($data,true).'------------'.var_export($response,true).'</pre>',true);}
   return false;
  }
  
  preg_match("/'_rnr_se': '([^']+)'/", $response['body'], $matches);
  self::$rnrse = $matches[1];
  return true;
 }
 
 //Phone number as format 15551232410, no +1-555-123-2410 or such nonsense. (You can actually send a maximum of 5 messages if you separate with commas!)
 public static function sendSMS($phonenumber,$message)
 {
  $data = array();
  google::authenticate($data);
  google::fixSSL($data);
  
  $data['post'] = array(
   '_rnr_se'     => self::$rnrse,
   'phoneNumber' => $phonenumber,
   'text'        => $message,
   'id'          => ''//Thread ID, leave blank.
  );
  
  $response = xhttp::fetch('https://www.google.com/voice/sms/send/',$data);
  
  if (!$response['successful'])
  {
   if (_DEBUG) {message('Google Voice sendSMS Error!<br><pre>$data = '.var_export($data,true).'------------'.var_export($response,true).'</pre>',true);}
   return false;
  }
  
  $value = json_decode($response['body']);
  
  if ($value->ok)
  {return true;}
  else
  {
   if (_DEBUG) {message('Google Voice sendSMS Error!<br>Error code from Google Voice was "'.$value->data->code,true);}//.'"<br><pre>$data = '.var_export($data,true).'------------'.var_export($response,true).'</pre>'
   return false;
  }
 }
 
 //Phone number(s) as array. Format must be like 15551232410, no +1-555-123-2410 or such nonsense.
 public static function sendBatchSMS($phonenumbers,$message)
 {
  message("sendBatchSMS is unimplemented.",true);
 }
}
?>