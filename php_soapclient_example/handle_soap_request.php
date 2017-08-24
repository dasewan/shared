<?php

require_once('simple_soap_client_class.php');

// GET parameter definition.
define('ACTION_INSERT', 'insert');
define('ACTION_READ', 'read');
define('INSERT_VALUE', 'value');
define('MODE_WSDL', 'wsdl');
define('MODE_NO_WSDL', 'no_wsdl');

// Server location definition.
define('LOCATION_WSDL', 'http://www.shared.com/php_soapclient_example/wsdl/simple_service_definition.wsdl');
define('LOCATION_NO_WSDL', 'http://www.shared.com/php_soapclient_example/wsdl/server_endpoint.php');

// Function definitions.

/**
 * Checks if the given parameters are set. If one of the specified parameters is not set,
 * die() is called.
 *
 * @param $parameters The parameters to check.
 */
function checkGETParametersOrDie($parameters) {
    foreach ($parameters as $parameter) {
        isset($_GET[$parameter]) || die("Please, provide '$parameter' parameter.");
    }
}

/**
 * Instantiates the SOAP client, setting the server location, depending on the mode.
 * If any error occurs, the page will die.
 *
 * @param $mode The SOAP mode, 'wsdl' or 'no_wsdl'.
 * @return SoapClient class instance.
 */
function instantiateSoapClient($mode) {
    if ($mode == MODE_WSDL) {
        $serverLocation = LOCATION_WSDL;
    } else {
        $serverLocation = LOCATION_NO_WSDL;
    }

    try {
        $soapClient = new SimpleSoapClient($mode, $serverLocation);
    } catch (Exception $exception) {
        die('Error initializing SOAP client: ' . $exception->getMessage());
    }

    return $soapClient;
}

// Flow starts here.

checkGETParametersOrDie(['mode', 'action']);

$mode = $_GET['mode'];
$action = $_GET['action'];

$soapClient = instantiateSoapClient($mode);

switch($action) {
    case ACTION_INSERT:
        checkGETParametersOrDie([INSERT_VALUE]);
        $value = $_GET[INSERT_VALUE];

        try {
            $response = $soapClient->insertData($value);
            echo "Response from SOAP service: $response<br>";
        } catch (Exception $exception) {
            die('Error inserting into SOAP service: ' . $exception->getMessage());
        }

        break;

    case ACTION_READ:
        try {
            $data = $soapClient->readData();
            echo "Received data from SOAP service:<br>";
            echo $data;
        } catch (Exception $exception) {
            die('Error reading from SOAP service: ' . $exception->getMessage());
        }

        break;

    default:
        die('Invalid "action" specified.');
        break;
}