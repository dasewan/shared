<?php
/**
 * Created by PhpStorm.
 * User: dongliang <781021164@qq.com>
 * Date: 2017/8/29
 * Time: 10:38
 */
error_reporting(0);
set_time_limit(0);
require 'common.php';
$db = new MysqliDb (DB_HOST, DB_USER, DB_PWD, 'fabu');
$notice = "http://www.shared.com/tysm_extend/index.php";
$base_dir = '/data/analyze/gylog_detail/';
$sub_dir = date("Y-m-d",strtotime("yesterday"));
$yesterday_log = $base_dir.$sub_dir;
$log_files = read_all_dir($yesterday_log);
foreach ($log_files['file'] as $k=>$v){
    preg_match('/\d{4,5}(?=_)/i',$v,$matches);
    $parentid = $matches[0];
    $db->where("id", $parentid);
    $user = $db->getOne("ftxia_user",'id,email,qq,mobile');
    if(strlen($user['email'])>5 && strlen($user['qq'])>5){
        $db->where("parentid",$parentid);
        $cms_user = $db->getOne("ftxia_cms_user",'qq');
        $email = $cms_user['qq']."@qq.com";
    }elseif(strlen($user['qq'])>5){
        $email = $user['qq']."@qq.com";
    }elseif(!empty($user['email'])){
        $email = $user['email'];
    }elseif(strlen($user['mobile'])>10){
//        $email = $user['mobile']."@qq.com";
    }
    if(strpos($v,'success')>0){
        $a = insert($v);
        $msg = '成功';
    }elseif(strpos($v, 'error')>0){
        $a = insert($v);
        $msg = '失败';
    }else{
        continue;
    }
    $count = count($a);
    $data['action'] = 'mail';
    $data['to'] = '781021164@qq.com';
    $body = <<<body
    您的网站.$domain.高佣接口调用$msg $count 次，
body;

    $data['body'] = $body;
    $data['subject'] = 'cms高佣接口统计';
    $a = common_curl($notice, $data);
    if($k>10)
        break;
}

function insert($file){
    global $db;
    $all = file($file);
    array_unshift($all,"Pointless");
    $numid = [];
    foreach ($all as $key => $value) {

        $remainder = $key%5;
        switch ($remainder) {
            case 0:
                # numid...
                $numid[] = str_replace('==========numid:', '', $value);
                break;
            case 1:
                # time...
                $time[] = $value;
                break;
            case 2:
                # pid...
                $pid[] = str_replace('==========PID:', '', $value);
                break;
            default:
                # code...
                break;
        }
    }

    foreach ($numid as $key => $value) {
        # code...
        if($key == 0)
            continue;
        $other_key = $key-1;
        $data['numid'] = $value;
        $data['pid'] = $pid[$other_key];
        $data['time'] = $time[$other_key];
        $db->insert('ftxia_analyze_gy', $data);
        unset($data);
    }
    return $numid;
}


