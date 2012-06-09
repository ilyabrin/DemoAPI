<?php
/*
  *   API Handler
  *  
  *   request example:
  *   POST: api.php?app_id=110&method=getUserInfo&token=f4a7784848652029e4bbf2e0b5363c6f
  *   POST: api.php?app_id=111&method=getUserInfo&token=f4a7784848652029e4bbf2e0b5363c6f
  *   POST: api.php?app_id=112&method=getUserInfo&token=f4a7784848652029e4bbf2e0b5363c6f
  *   POST: api.php?app_id=113&method=getUserInfo&token=f4a7784848652029e4bbf2e0b5363c6f
  *   POST: api.php?app_id=114&method=getUserInfo&token=f4a7784848652029e4bbf2e0b5363c6f
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

$apiMethods = array(
	"getUserInfo",
	"setUserInfo",
	"getUserOnline",
	"getServerTime"
);

$format = array();  // head - header http, type - default data format
// необязательный параметр
$format['head'] = (!empty($_REQUEST['format']) && ($_REQUEST['format']==='xml')) ? 'Content-Type:text/xml' : 'Content-type: application/json';
$format['type'] = 'json';

// служебные переменные
$app_id = (!empty($_REQUEST['app_id'])) ? stripcslashes(strip_tags($_REQUEST['app_id'])) : die('app_id undefined.');
$method = (!empty($_REQUEST['method'])) ? stripcslashes(strip_tags($_REQUEST['method'])) : die('method undefined.');

// от клиента пришла такая подпись запроса
$requested_token  = (!empty($_REQUEST['token']))  ? stripcslashes(strip_tags($_REQUEST['token']))  : die('broken token.');

	$db = new db("mysql:host=localhost;dbname=$db_name", $db_user, $db_pass);
	$db->setErrorCallbackFunction("ErrorHandler");

	$sql = "SELECT `app_secret` FROM `api` WHERE ( `app_id`='$app_id' )";

	$secret = $db->run($sql);

	// данные для проверки
	$api_query = array(
	  "app_id" => intval($app_id),
	  "method" => $method
	);

   ksort($api_query);
   $query = ( array_implode( '=', '', $api_query ) );
   $computed_token = md5($query.$secret[0]['app_secret']); // вычисление эталонного хэша

// если подпись запроса верна, то обрабатываем метод из запроса
if ($computed_token === $requested_token)
   {
      header($format['head']);
      if ('getUserInfo'   == $method) { echo call('setUserInfo',$a=array("name"=>"First","lastname"=>"second") ); }    //$api->call('getUserInfo', $args); // $args is optional
      if ('getUserOnline' == $method) { echo json_encode($api_userdata_2); }
      if ('getServerTime' == $method) { echo json_encode($api_userdata_3); }
   }
	/* prototype
	///////////////
   	if (in_array($method,$apiMethods)) {
		if ($args) {
			call($method, $args);
		}
		call($method);
	}
	else {
		return $error; //method is not exist
	}
	//////////////
	*/
   else
   {
      echo json_encode($api_error);
      exit;
   }


// вызов функции из api
function call($function, $args=array()) {
//TODO: $apiMethods должна быть видимой глобально (ответ: делай класс)
$apiMethods = array(
	"getUserInfo",
	"setUserInfo",
	"getUserOnline",
	"getServerTime"
);
	if ($args) {
		if (!is_array($args)) {
			return '{"error":"args is not array"}';
		}
	}
	
	if (!$function) {
			return '{"error":"function not found"}';
	}
	
	if ( in_array($function, $apiMethods) ) {
		if ($args) {
			return call_user_func_array($function, $args);
		}
			return call_user_func($function);	
	}
}

// объявление методов api
function getUserInfo() {
	return '{"success":"You are superb!"}';
}

function setUserInfo($name, $lastname) {
	return $name.' '.$lastname;
}
?>