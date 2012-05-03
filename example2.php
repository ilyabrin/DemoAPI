<?php
error_reporting(E_ALL);

require_once('themengn/templates.class.php');
require_once('demo.data.php');

$template = new template;

$template->assign(array(
        "user" => "1",
        "name" => "John",
        "lastname" => "Dou",
        "age" => 25,
        "sex"  => "male",
        "phone" => "+79993553355",
        "email" => "email@mail.com",
        "street" => "Sunset Street",
        "city" => "Metrocity",
        "country" => "USA"
        )
); 

$template->render('demo/userinfo.html');
?>