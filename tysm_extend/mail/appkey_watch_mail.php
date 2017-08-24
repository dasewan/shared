<?php
require './PHPMailerAutoload.php';

set_time_limit(0);


$normal_appkey = [
    27 => '7efcd8c929bcbfd5b016c00a711f5759',
    28 => 'a5937eff64a8a3846e8e578938ba5629',
    29 => '475bc4604a9b8ff3fd14cddfbd281b14',
    31 => 'bb5e2c8c1c4b94480182b27de9e59821',
    32 => 'f8db36f4eb09c494a1619ebe4d8db028',
    33 => '2b3b9fe4c02990377fa615eeb3369ec9',
    34 => '43b6bdf2af89caf5ddd84d0a7ca68f6d',
    35 => '2c74451cffd26c2367c8d3a2e1c49860',
    36 => '2e07774ef899d0b043da816604908d89',
    37 => '8c7c866a13f952e17395811408d2fd1b',
    41 => 'a154a70f923c579313aa9c47ed7c34e2',
    26 => 'd3ffef34f5fd13c6229f055833492eef',
    43 => '907426606e10139de6617963c3e73cff',
    50 => '8435b54945aa9d41c9696ae61584eead',
    51 => '32baa8985154c9f45d6f00c04416e904',
    94 => 'ff546e9b1f420b29ed8e917b58f09d4b',
    64 => '0e30bc8c5f449ca9cdadaa730968d2ab',
    56 => '774f7754121986456deda3863db46638',
    57 => '265fa03ebcad6efb91c1f7abdc759b57',
    58 => '00078df41f2e452780dc39890479983d',
    60 => '5d36828d4460031e017fc3ac036fe4c5',
    61 => '18d2ed342e000a445f68b972920afeeb',
    62 => '9567f61c97ee09f33cf0200fc2765fcd',
    66 => '8c8254dc524ea5ac41f3675033bfc260',
    67 => '32cfe444b37709abb656fd5dbfa182da',
    68 => '95414e4cf8fe1dbc8cbd6c309b3c65a0',
    69 => 'dc36ed50d5e2b6d6d434f9db4050dc47',
    93 => '7a677bb4477ae2dd371add568dd19e23',
    74 => '9ab33b0ea61bf6155af3e599d105416d',
    95 => '322a1ea39e41ac31911145ff38087a52',
    96 => 'c8e8672d46c9a4120fa6f220cf82d38a',
    97 => '3b50004ac6c37503d2a34bda5c8f9ff0',
    98 => '57ecd7316c52ffcbdea03690ea7db2b8',
    99 => 'b19f7840920bfe7e12558f646771304a',
    100 => '11fcdfee18ec6be96bb61207fa943677',
    101 => '1bbd8d57331460e50ed182eef1267ddf',
    92 => '6c5de1b510e8bdd0bc40eff99dcd03f8',
    104 => 'b4cbf5c3fe983ee1e3473efa386fae7d',
    105 => 'b884161fdfd274713ccfc22e97620d66',
    106 => 'ad06f1a61d253f34e1ae41ef3851ed05',
    107 => '884738b4332ababd678ca505f4e04f4d',
    108 => '8ff293c41259a2fe9e725795714d0df2',
    109 => '366444be7afcee6161a4ad38ddcc1cf0'

];


$con = mysql_connect("139.224.227.186", "sphinx", "sphinx");
if (!$con) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db("fabu", $con);
$sql = "select * from ftxia_app;";
$result = mysql_query($sql);
$a = "";

while ($row = mysql_fetch_array($result)) {
    if ($normal_appkey[$row['cid']] != $row['appKey']) {
        $body = $row['cid'].'::old_appkey::'.$normal_appkey[$row['cid']].'::new_appkey'.$row['appKey']."::new_data::".json_encode($row);
        $body = $row['cid']."\r\n<br/>old_appkey::".$normal_appkey[$row['cid']]."\r\n<br/>new_appkey::".$row['appKey']."\r\n<br/>apid::".$row["apid"]."\r\n<br/>ppid::".$row['ppid']."\r\n<br/>title::".$row['title']."\r\n<br/>add_time::".date('Y-m-d H:i:s',$row['add_time'])."\r\n<br/>session_time:".date('Y-m-d H:i:s',$row['session_time'])."\r\n<br/>update_time:".$row['update_time'];
        think_send_mail("781021164@qq.com",$body);
        think_send_mail("1378864868@qq.com",$body);
        think_send_mail("1454123008@qq.com",$body);
        think_send_mail("1661209460@qq.com",$body);
        think_send_mail("1060546786@qq.com",$body);
    }
}

    function think_send_mail($to,  $body = '', $attachment = null){

//        vendor('PHPMailer.PHPMailerAutoload'); //从PHPMailer目录导class.phpmailer.php类文件
        
        $mail = new PHPMailer;

        $mail->isSMTP();
//        $mail->SMTPDebug = 2;
//        $mail->Debugoutput = 'html';
        $mail->Host = 'smtp.163.com';
        $mail->Port = 25;
        $mail->SMTPAuth = true;
        $mail->Username = '13156956520@163.com';
        $mail->Password = 'Qwer1234';
        $mail->setFrom('13156956520@163.com', '一手单');
        $mail->addAddress($to, 'John Doe');
        $mail->Subject = 'appKey 变动';
        $mail->msgHTML(222, dirname(__FILE__));
        $mail->AltBody = $body;
        $mail->Body = $body;
        if (!$mail->send()) {
            $a = $mail->ErrorInfo;
            return $mail->ErrorInfo;
        } else {
            return 1;
        }

    }


