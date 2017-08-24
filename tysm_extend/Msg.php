<?php
/**
 * Created by PhpStorm.
 * User: dongliang <781021164@qq.com>
 * Date: 2017/8/24
 * Time: 17:18
 */

//error_reporting(0);
function PHPMailerAutoload($classname)
{
    $classname = str_replace('\\', '/', $classname);
    $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . '' . $classname . '.php';
    if (is_readable($filename)) {
        echo $filename;
        require $filename;
    }
}

if (version_compare(PHP_VERSION, '5.1.2', '>=')) {
    //SPL autoloading was introduced in PHP 5.1.2
    if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
        spl_autoload_register('PHPMailerAutoload', true, true);
    } else {
        spl_autoload_register('PHPMailerAutoload');
    }
} else {
    /**
     * Fall back to traditional autoload for old PHP versions
     * @param string $classname The name of the class to load
     */
    function __autoload($classname)
    {
        PHPMailerAutoload($classname);
    }
}

class Msg
{
    private $mobile;
    private $code;
    public function __construct()
    {
        $this->mobile = $_POST['mobile'];
        $this->code = $_POST['code'];
    }

    public function start()
    {
        $mobile = $this->mobile;
        $code = $this->code;
        $c = new Taobaotop\TopClient;
        $c->appkey = MSG_APPKEY;
        $c->secretKey = MSG_SECRETKEY;
        $req = new Taobaotop\request\AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend($code);
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("一手单");
        $req->setSmsParam("{\"code\":\"" . $code . "\",\"product\":\"一手单\"}");
        $req->setRecNum($mobile);
        $req->setSmsTemplateCode('SMS_52235140');
        $resp = $c->execute($req);
        if (isset($resp->result)) {
            if ($resp->result->success) {
                return ['code'=>200, 'msg'=>'msg has sended'];
            } else {
                return "发送失败";
            }
        } else {
            $result = "发送失败";
            if (isset($resp->sub_msg))
                $result = trim($resp->sub_msg);
            if (empty($result) || $result == "")
                return "发送失败";
            else
                return $result;
        }
    }
}