<?php
/**
 * Created by PhpStorm.
 * User: dongliang <781021164@qq.com>
 * Date: 2017/8/24
 * Time: 15:32
 */
function common_curl($url, $param = []){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);    //表示需要response header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    if(count($param)>0){
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$param);
    }

    $result = curl_exec($ch);

    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($http_status == '200'){
        return ['code'=>200, 'msg'=>'normal status', 'data'=>json_encode($result)];
    }else{
        return ['code'=>$http_status, 'msg'=>'die status'];
    }

}
