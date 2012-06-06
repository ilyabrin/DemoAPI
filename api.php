<?php
/*
  *   API Handler
  *  
  *   request example:
  *   POST: api.php?app_id=110&method=getUserInfo&token=f4a7784848652029e4bbf2e0b5363c6f
  *   
 */
require_once('pdo/class.db.php');
require_once('api.utils.php');
require_once('demo.data.php');

require_once('config.php');

error_reporting(E_ALL);
ini_set('display_errors','On');

function ErrorHandler($error) { echo ($error); }

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

// необязательный параметр
$format = (!empty($_REQUEST['format']) && ($_REQUEST['format']==='xml')) ? 'Content-Type:text/xml' : 'Content-type: application/json';

// служебные переменные
$app_id = (!empty($_REQUEST['app_id'])) ? stripcslashes(strip_tags(mysql_real_escape_string($_REQUEST['app_id']))) : die('app_id undefined.');
$method = (!empty($_REQUEST['method'])) ? stripcslashes(strip_tags(mysql_real_escape_string($_REQUEST['method']))) : die('method undefined.');

$requested_token  = (!empty($_REQUEST['token']))  ? stripcslashes(strip_tags(mysql_real_escape_string($_REQUEST['token'])))  : die('broken token.');

   $db = new db("mysql:host=localhost;dbname=$db_name", $db_user, $db_pass);
   $db->setErrorCallbackFunction("ErrorHandler");
         
   $sql = "SELECT `app_secret` FROM `api` WHERE ( `app_id`='$app_id' )";

   $secret = $db->run($sql);

   $api_query = array(
      "app_id" => intval($app_id),
      "method" => $method
   );

   ksort($api_query);
   $query = ( array_implode( '=', '', $api_query ) );
   $computed_token = md5($query.$secret[0]['app_secret']);

if ($computed_token === $requested_token)
   {
      header($format);
      if ('getUserInfo'   == $method) { echo json_encode($api_userdata); }
      if ('getUserOnline' == $method) { echo json_encode($api_userdata_2); }
      if ('getServerTime' == $method) { echo json_encode($api_userdata_3); }
   }
   else
   {
      echo json_encode($api_error);
      exit;
   }
?>