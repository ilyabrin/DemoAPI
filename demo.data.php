<?php
// example api.php response :: getUserInfo
$api_userdata = array(
        "user" => "1",
        "name" => "John",
        "lastname" => "Dou",
        "age" => 25,
        "sex"  => "male",
        "phone" => "+79993553355",
        "email" => "email@mail.com",
        "address" => array(
                "Sunset Street",
                "Metrocity",
                "USA"
        )
);


// example api.php response :: getUserOnline
$api_userdata_2 = array(
        "user" => "1",
        "online" => "yes"
        );


// example api.php response :: getServerTime
$api_userdata_3 = array(
        "time" => time(),
        "date" => date("H:i:s")
);

$api_error = array(
    "error" => "Ошибки с токенами, командир."
);

$method_error = array(
    "error" => "Вы пытаетесь вызвать несуществующий метод. Это так стыдно."
);

?>