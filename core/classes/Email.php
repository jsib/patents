<?php

namespace Core;

class Email
{
    private function getData($smtp_conn)
    {
        $data="";
        while($str = fgets($smtp_conn,515))
        {
            $data .= $str;
            if(substr($str,3,1) == " ") { break; }
        }
        return $data;
    }

    /**
     * Send email
     * 
     * @param string $subject Subject of email, should be in UTF-8
     * @param string $text Body of email
     */
    public function sendMail($recipient, $subject = "test", $text = "test mail")
    {
        $header="Date: ".date("D, j M Y G:i:s")." +0300\r\n";
        $header.="From: =?utf-8?Q?".str_replace("+","_",str_replace("%","=",urlencode('Inform')))."?= <inform@acoustic.ru>\r\n";
        $header.="X-Mailer: The Bat! (v3.99.3) Professional\r\n";
        $header.="Reply-To: =?utf-8?Q?".str_replace("+","_",str_replace("%","=",urlencode('Inform')))."?= <inform@acoustic.ru>\r\n";
        $header.="X-Priority: 3 (Normal)\r\n";
        $header.="Message-ID: <172562218.".date("YmjHis")."@acoustic.ru>\r\n";
        $header.="To: =?utf-8?Q?".str_replace("+","_",str_replace("%","=",urlencode('Inform')))."?= <inform@acoustic.ru>"."\r\n";
        $header.="Subject: =?utf-8?Q?".str_replace("+","_",str_replace("%","=",urlencode($subject)))."?=\r\n";
        $header.="MIME-Version: 1.0\r\n";
        $header.="Content-Type: text/html; charset=utf-8\r\n";
        $header.="Content-Transfer-Encoding: 8bit\r\n";
        
        $smtp_conn = fsockopen("192.168.10.8", 25, $errno, $errstr, 10);
        if(!$smtp_conn) {print "соединение с серверов не прошло"; fclose($smtp_conn); exit;}
        $data = $this->getData($smtp_conn);
        fputs($smtp_conn,"EHLO vasya\r\n");
        $code = substr($this->getData($smtp_conn),0,3);
        if($code != 250) {print "ошибка приветствия EHLO"; fclose($smtp_conn); exit;}
        fputs($smtp_conn,"AUTH LOGIN\r\n");
        $code = substr($this->getData($smtp_conn),0,3);
        if($code != 334) {print "сервер не разрешил начать авторизацию"; fclose($smtp_conn); exit;}

        fputs($smtp_conn,base64_encode("inform@acoustic-group.net")."\r\n");
        $code = substr($this->getData($smtp_conn),0,3);
        if($code != 334) {print "ошибка доступа к такому юзеру"; fclose($smtp_conn); exit;}


        fputs($smtp_conn,base64_encode("Truth123")."\r\n");
        $code = substr($this->getData($smtp_conn),0,3);
        if($code != 235) {print "не правильный пароль"; fclose($smtp_conn); exit;}

        $size_msg=strlen($header."\r\n".$text);

        fputs($smtp_conn,"MAIL FROM:<inform@acoustic.ru> SIZE=".$size_msg."\r\n");
        $code = substr($this->getData($smtp_conn),0,3);
        if($code != 250) {print "сервер отказал в команде MAIL FROM"; fclose($smtp_conn); exit;}

        fputs($smtp_conn,"RCPT TO:<$recipient>\r\n");
        $code = substr($this->getData($smtp_conn),0,3);
        if($code != 250 AND $code != 251) {print "Сервер не принял команду RCPT TO"; fclose($smtp_conn); exit;}

        fputs($smtp_conn,"DATA\r\n");
        $code = substr($this->getData($smtp_conn),0,3);
        if($code != 354) {print "сервер не принял DATA"; fclose($smtp_conn); exit;}

        fputs($smtp_conn,$header."\r\n".$text."\r\n.\r\n");
        $code = substr($this->getData($smtp_conn),0,3);
        if($code != 250) {print "ошибка отправки письма. номер ошибки $code"; fclose($smtp_conn); exit;}

        fputs($smtp_conn,"QUIT\r\n");
        fclose($smtp_conn);
    }
}