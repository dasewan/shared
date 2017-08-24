<?php

/**
 * Created by PhpStorm.
 * User: dongliang <781021164@qq.com>
 * Date: 2017/8/24
 * Time: 14:53
 */
require '../mail/PHPMailerAutoload.php';
Class Mail
{
    private $to;
    private $subject;
    private $body;

    public function __construct()
    {
        $this->to = $_POST['to'];
        $this->subject = $_POST['subject'];
        $this->body = $_POST['body'];
    }

    public function start()
    {
        $mail = new PHPMailer;

        $mail->isSMTP();
//        $mail->SMTPDebug = 2;
//        $mail->Debugoutput = 'html';
        $mail->Host = MAIL_HOST;
        $mail->Port = MAIL_PORT;
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PWD;
        $mail->setFrom(MAIL_USERNAME, '一手单');
        $mail->addAddress($this->to, 'John Doe');
        $mail->Subject = $this->subject;
        $mail->msgHTML(222, dirname(__FILE__));
        $mail->AltBody = $this->body;
        $mail->Body = $this->body;
        if (!$mail->send()) {
            return ['code'=>400, 'msg'=>$mail->ErrorInfo];
        } else {
            return ['code'=>200, 'msg'=>'mail has sended'];
        }
    }
}