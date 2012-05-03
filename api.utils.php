<?

function array_implode( $glue, $separator, $array ) {
    if ( ! is_array( $array ) ) return $array;
    $string = array();
    foreach ( $array as $key => $val ) {
        if ( is_array( $val ) )
            $val = implode( ',', $val );
        $string[] = "{$key}{$glue}{$val}";
    } //.foreach
    return implode( $separator, $string );
} //.array_implode();


function sendRequest( $url, $postfields, $httpheader = array("Accept: application/json"))
{
    $ch = curl_init();
    
    // установка URL и других необходимых параметров
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1); //будет POST
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    
    // загрузка страницы и выдача её браузеру
    //$result = curl_exec($ch);
    curl_exec($ch);
    
    // завершение сеанса и освобождение ресурсов
    curl_close($ch);
    //return $result;
}
?>