<?php
/**
 * Created by PhpStorm.
 * User: dongliang
 * Date: 2017/8/24
 * Time: 9:01
 */
function echoo($echo){
    return "ECHO: ".$echo;
}

$server = new SoapServer(null,
    array('uri' => "urn://tyler/res"));
$server->addFunction('echoo');
$server->handle();