<?php

/* ------------------------------------------------------------------------------
  : DemoAPI | Пример защищенного серверного взаимодействия с API / example.php :
 -------------------------------------------------------------------------------*/

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

require_once('api.utils.php');

$API_SECRET =  "78e4cbbdc7f5383d71fc2ea34c9d9086";
$APP_ID     =  110;
$API_URL    =  "http://smolget.net/sb/api.php";

$method = 'getUserInfo';  // or: getUserOnline, getServerTime

$api_query = array(
       "app_id" => $APP_ID,
       "method" => $method
);

ksort($api_query);
$query = array_implode('=', '', $api_query);

$token = md5($query.$API_SECRET);
$api_query_str = array_implode( '=', '&', $api_query)."&token=".$token;

sendRequest($API_URL, $api_query_str);

?>