<?php
/**
 * Created by PhpStorm.
 * User: dongliang <781021164@qq.com>
 * Date: 2017/8/24
 * Time: 15:05
 */
$config = [
    'MAIL_HOST'=>'smtp.163.com',
    'MAIL_PORT'=>25,
    'MAIL_USERNAME'=>'13156956520@163.com',
    'MAIL_PWD'=>'Qwer1234',
    'MSG_APPKEY'=>'23660835',
    'MSG_SECRETKEY'=>'0255455d3573d91471530603a9d6d7a9',
];
foreach ($config as $k=>$v){
    define($k, $v);
}