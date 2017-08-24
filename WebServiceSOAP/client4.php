<?php
/**
 * Created by PhpStorm.
 * User: dongliang
 * Date: 2017/8/24
 * Time: 9:02
 */
$client = new SoapClient(null, array(
    'location' => "http://www.shared.com/WebServiceSOAP/server4.php",
    'uri' => "urn://tyler/req"));

$result = $client->
__soapCall("echoo", array(123));

print $result;
