<?php
/**
 * Created by PhpStorm.
 * User: dongliang
 * Date: 2017/8/23
 * Time: 15:25
 */

// $client = new SoapClient('http://localhost/php/soap/math.wsdl');
/*$client = new SoapClient("http://www.shared.com/WebServiceSOAP/server2.php?WSDL");
try{
    $result = $client->div(8, 2); // will cause a Soap Fault if divide by zero
    print "The answer is: $result";
}catch(SoapFault $e){
    print "Sorry an error was caught executing your request: {$e->getMessage()}";
}*/



try {

    // options for ssl in php 5.6.5
    $opts = array(
        'ssl' => array(
            'ciphers' => 'RC4-SHA',
            'verify_peer' => false,
            'verify_peer_name' => false
        )
    );

// SOAP 1.2 client
    $params = array(
        'encoding' => 'UTF-8',
        'verifypeer' => false,
        'verifyhost' => false,
        'soap_version' => SOAP_1_2,
        'trace' => 1,
        'exceptions' => 1,
        'connection_timeout' => 180,
        'stream_context' => stream_context_create($opts)
    );

    $wsdlUrl = 'http://www.shared.com/WebServiceSOAP/server2.php?WSDL';
    $oSoapClient = new SoapClient($wsdlUrl, $params);





/*    $opts = array(
        'http' => array(
            'user_agent' => 'PHPSoapClient'
        )
    );
    $context = stream_context_create($opts);

    $wsdlUrl = 'http://www.shared.com/WebServiceSOAP/server2.php?WSDL';
    $soapClientOptions = array(
        'stream_context' => $context,
        'cache_wsdl' => WSDL_CACHE_NONE
    );
    libxml_disable_entity_loader(false);
    $client = new SoapClient($wsdlUrl, $soapClientOptions);*/

}
catch(Exception $e) {
    echo $e->getMessage();
}