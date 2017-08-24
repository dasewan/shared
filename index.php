<?php
/*
 * 请勿使用windows下的记事本修改本文件。推荐使用 notepad++,SublimeText
 * 版本 v1.4
 * 版本日志：
 *	修复手机页面在商品详情页,点击返回,跳转到首页后,自动跳回商品详情页;
 * */

 error_reporting(0);
$appId 	= "7453";  // 站点的APPID （请勿修改）
$appKey = "f344539edd8e73a2b0e0f23a7e1f2ca3";// 站点的APP KEY（请勿修改）
$urlid = "22610";// 站点的分配id（请勿修改）
$version= "2.0"; // 程序版本号（请勿修改）


//===============================================================================
//===============================================================================
//===============================================================================
//================               请勿修改以下程序            ====================
//===============================================================================
//===============================================================================
//===============================================================================

$furl = "/index.php?";
if (!function_exists('curl_init')) {
	header("Content-type: text/html; charset=utf-8");
 echo 'curl <span style="color: red">未开启</span>' . '<br>';
	exit();
 }
$get=array();

$cache = new CacheHelper();

$host = "http://web.yishoudan.com";

//删除多余参数
if($_SERVER['PHP_SELF']){
	$host =str_replace($_SERVER['PHP_SELF'],"",$host);
}
$requestMethod = strtoupper($_SERVER["REQUEST_METHOD"]);
$rand = rand(1,5);
if($rand%5 == 0)
    $cache->Del();

if (isset($_REQUEST["clean"])) {
	$cache->clean();
	echo "var text='已清除缓存';";
	exit();
}
$key = md5($_SERVER['REQUEST_URI'] . CacheHelper::isMobile() . CacheHelper::isIPad() . CacheHelper::isIPhone() . CacheHelper::isMicroMessenger());
if ($requestMethod == "GET") {

	$cacheData = $cache->Get($key);
	if ($cacheData !== false) {
		echo $cacheData;
		exit;
	}
}

$httpHelper = new HttpHelper($appId, $appKey,$version,$urlid);

$html = $httpHelper->getHtml($host);


$isjson = (array)json_decode($html);
if (!empty($isjson["error"])) {
	if ($isjson["error"]==1) {
		header("location: ".$isjson["msg"]);
		die;
	}	
}
if ($requestMethod == "GET" && !empty($html)) {
	$cache->Set($key, $html, 60);
}
echo $html;

//获取数据
class HttpHelper
{
	protected $appId;
	protected $key;
	protected $version;
    protected $urlid;

	public function __construct($appId, $key,$version,$urlid)
	{
		$this->appId	=	$appId;
		$this->key		=	$key;
		$this->version	=	$version;
        $this->urlid	=	$urlid;
	}


	/**
	 * 发送请求
	 * @param $url
	 * @return string
	 */
	public function getHtml($url)
	{
		//echo $url;
		//获取当前设备
		$php_self="";
		$ua =  $_SERVER["HTTP_USER_AGENT"];
		if ($_SERVER["PHP_SELF"]!="/index.php") {
			$php_self = explode("/index.php", $_SERVER["PHP_SELF"]);
			$php_self = $php_self[0];
		}	
		$clientIp = $this->get_real_ip();
		$header = array(
			"APPID"			=> $this->appId,
			"APPKEY"		=> $this->key,
			"VERSION"		=> $this->version,
            "URLID"		    => $this->urlid,
			"HOST"			=> $_SERVER["HTTP_HOST"],			
			"REQUEST_URI"	=> $_SERVER["REQUEST_URI"],
			"PHP_SELF"		=> $php_self
		);
		
		if (!empty($clientIp)) {
			$header["CLIENT-IP"] = $clientIp;
		}
		if($_GET['p']){
			$header["p"]=$_GET['p'];
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);
		//ssl
		if (is_array($header))
		
		{
			$postBodyString = "";
			foreach ($header as $k => $v)
			{
				$postBodyString .= "$k=" . urlencode($v) . "&";
			}
			
			if ($_POST) {
				
				foreach ($_POST as $k => $v)
				{
					$postBodyString .= "$k=" . urlencode($v) . "&";
				}
			}
			unset($k, $v);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
		}
		$r = curl_exec($ch);

		$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);		
		$header = mb_substr($r, 0, $headerSize);
		$r = mb_substr($r, $headerSize);
		curl_close($ch);
		unset($ch);
		
		return $r;
	}
	
	function get_real_ip()
	{
		if (@$_SERVER["HTTP_X_FORWARDED_FOR"]) {
			$ip = @$_SERVER["HTTP_X_FORWARDED_FOR"];
		} elseif (@$_SERVER["HTTP_CLIENT_IP"]) {
			$ip = @$_SERVER["HTTP_CLIENT_IP"];
		} elseif (@$_SERVER["REMOTE_ADDR"]) {
			$ip = @$_SERVER["REMOTE_ADDR"];
		} elseif (getenv("HTTP_X_FORWARDED_FOR")) {
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		} elseif (getenv("HTTP_CLIENT_IP")) {
			$ip = getenv("HTTP_CLIENT_IP");
		} elseif (getenv("REMOTE_ADDR")) {
			$ip = getenv("REMOTE_ADDR");
		} else {
			$ip = "";
		}
		return $ip;
	}

	public function getIsAjaxRequest()
	{
		return isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] === "XMLHttpRequest";
	}

}

//设置
class CacheHelper
{
	protected $dir = "";

	public function __construct()
	{
		$this->dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . "cache";

		if (is_dir($this->dir)) {
			return;
		}
		@mkdir($this->dir);
	}

	public function Set($key, $value, $expire = 360)
	{
		$data = array(
			"time" => time(),
			"expire" => $expire,
			"value" => $value
		);
		@file_put_contents($this->dir . DIRECTORY_SEPARATOR . md5($key) . "cache", serialize($data));
	}

	public function Get($key)
	{

		$file = $this->dir . DIRECTORY_SEPARATOR . md5($key) . "cache";
		if (!file_exists($file)) {
			return false;
		}
		$str = @file_get_contents($file);
		if (empty($str)) {
			return false;
		}
		$data = @unserialize($str);
		if (!isset($data["time"]) || !isset($data["expire"]) || !isset($data["value"])) {
			return false;
		}
		if ($data["time"] + $data["expire"] < time()) {
			return false;
		}
		return $data["value"];
	}
	
	public function Del()
	{
		$hostdir=dirname(__FILE__)."/cache/";
		$filesArr= scandir($hostdir);
		unset($filesArr[0]);
		unset($filesArr[1]);
		foreach ($filesArr as $key => $value) {
			$str = @file_get_contents($hostdir.$value);
			if (empty($str)) {
				unlink($hostdir.$value);
			}
			$data = @unserialize($str);
			if (($data["time"]+86400)<time()){
				unlink($hostdir.$value);
			}
		}
	}
	
	static function isMobile()
	{
		$ua = @$_SERVER["HTTP_USER_AGENT"];
		return preg_match("/(iphone|android|Windows\sPhone)/i", $ua);
	}

	public function clean()
	{
		if (!empty($this->dir) && is_dir($this->dir)) {
			@rmdir($this->dir);
		}
		$files = scandir($this->dir);
		foreach ($files as $file) {
			@unlink($this->dir . DIRECTORY_SEPARATOR . $file);
		}
	}


	static function isMicroMessenger()
	{
		$ua = @$_SERVER["HTTP_USER_AGENT"];
		return preg_match("/MicroMessenger/i", $ua);
	}

	static function isIPhone()
	{
		$ua = @$_SERVER["HTTP_USER_AGENT"];
		return preg_match("/iPhone/i", $ua);
	}

	static function isIPad()
	{
		$ua = @$_SERVER["HTTP_USER_AGENT"];
		return preg_match("/(iPad|)/i", $ua);
	}
}