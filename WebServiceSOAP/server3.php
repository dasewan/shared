<?php
/**
 * Created by PhpStorm.
 * User: dongliang
 * Date: 2017/8/23
 * Time: 18:12
 */

class Service
{
    public function Hello()
    {
        echo 'hello good';
    }
    public function Add($a,$b)
    {
        return $a+$b;
    }
}
$server=SoapServer('Service.php',array('soap_version'=>soap_1_2));
$server->setClass('Service');//注册Service类的所有方法
$server->handle();//处理请求