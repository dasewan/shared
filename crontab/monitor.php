<?php
/**
 * Created by PhpStorm.
 * User: dongliang <781021164@qq.com>
 * Date: 2017/8/24
 * Time: 15:31
 */
require 'common.php';
$notice = "http://www.shared.com/tysm_extend/index.php";


/************************app_index****************************/
$url = 'http://app.wntaoke.com/?appKey=2e07774ef899d0b043da816604908d89&m=ios_index&list=1';
$result = common_curl($url);
if($result['code'] == 200 && strlen($result['data'])>6000){

}elseif($result['code'] == 200){
    $data['action'] = 'mail';
    $data['to'] = '781021164@qq.com';
    $data['body'] = '服务器没问题，数据有问题';
    $data['subject'] = '服务器没问题，数据有问题';
    $a = common_curl($notice, $data);
}else{
    $data['action'] = 'mail';
    $data['to'] = '781021164@qq.com';
    $data['body'] = '服务器有问题'.$result['code'];
    $data['subject'] = '服务器没问题，数据有问题';
    $a = common_curl($notice, $data);
}
/************************app_index****************************/

/************************app_index****************************/
/************************app_index****************************/
