<?php
/**
 * Created by PhpStorm.
 * User: dongliang <781021164@qq.com>
 * Date: 2017/8/24
 * Time: 10:41
 */

require 'config.php';
define('ACTION_MAIL', 'mail');
define('ACTION_MSG', 'msg');
//检查参数
function checkGETParametersOrDie($parameters) {
    foreach ($parameters as $parameter) {
        isset($_POST[$parameter]) || die("Please, provide '$parameter' parameter.");
    }
}

function instantiateSoapClient($action) {
    if ($action == ACTION_MAIL) {
        require_once('Mail.php');
        $service = new Mail();
    } elseif($action == ACTION_MSG) {
        require_once('Msg.php');
        $service = new Msg();
    }

    return $service;
}

// Flow starts here.

checkGETParametersOrDie(['action']);

$action = $_POST['action'];
$service = instantiateSoapClient($action);

echo json_encode($service->start());