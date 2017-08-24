<?php
error_reporting(0);
// $yesterday = date('Y-m-d',strtotime("yesterday"));
$yesterday = date('Y-m-d',strtotime("today"));
$yesterday = "http://web.yishoudan.com/data/log/gylog/".$yesterday.'--gysuccess.txt';
$all = file($yesterday);
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

$con = mysql_connect("139.224.227.186","sphinx","sphinx");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("fabu", $con);

$insert_val_str = '';

foreach ($numid as $key => $value) {
	# code...
	if($key == 0)
		continue;
	$other_key = $key-1;
	// $insert_val_str .=  "($value,$pid[$other_key],$time[$other_key]),";
	if($key%20 == 0){
		$insert_val_str .=  "($value,'".$pid[$other_key]."','".$time[$other_key]."')";
		$sql = "insert into ftxia_analyze_gy (numid, pid, `time`) values ".$insert_val_str;
		// echo $sql."<br/><br/><br/>";
		mysql_query($sql);
		$insert_val_str = '';
	}else{
		// $insert_val_str .=  "($value,$pid[$other_key],$time[$other_key]),";
		$insert_val_str .=  "($value,'".$pid[$other_key]."','".$time[$other_key]."'),";
	}
}
mysql_close($con);

