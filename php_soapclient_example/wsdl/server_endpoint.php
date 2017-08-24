<?php

require_once('../simple_soap_server_class.php');

$server = new SoapServer('http://www.shared.com/php_soapclient_example/wsdl/simple_service_definition.wsdl');
$server->setClass('SimpleSoapServer');
$server->handle();
