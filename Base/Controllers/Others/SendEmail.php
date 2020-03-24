<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SendEmail
 *
 * @author JOSE MEZA
 */
class SendEmail {

    private $username = null;
    private $password = null;
    private $from = null;
    private $to = null;
    private $subject = null;
    private $message = null;
    private $host = null;
    private $port = null;
    private $smtp = null;
    private $secure = null;
    private $headers = null;
    private $prepared = false;
    private $emailcount = 0;

    public function __construct() {
        $this->smtp = new \PHPMailer\PHPMailer\PHPMailer();
    }

    public function getEmailCount() {
        return $this->emailcount;
    }

    public function setHOST($host) {
        if ($host !== null && $host !== '') {
            $this->host = $host;
            return true;
        }
        return false;
    }

    public function setUSERNAME($user) {
        if ($user !== null && $user !== '') {
            $this->username = $user;
            return true;
        }
        return false;
    }

    public function setPASSWORD($pass) {
        if ($pass !== null && $pass !== '') {
            $this->password = $pass;
            return true;
        }
        return false;
    }

    public function setPORT($port) {
        if ($port !== null && $port !== '') {
            $this->port = $port;
            return true;
        }
        return false;
    }

    public function setFROM($from) {
        if ($from !== null && $from !== '') {
            $this->from = $from;
            return true;
        }
        return false;
    }

    public function setTO($to) {
        if ($to !== null && $to !== '') {
            $this->to = $to;
            return true;
        }
        return false;
    }

    public function setSUBJECT($subject) {
        if ($subject !== null && $subject !== '') {
            $this->subject = $subject;
            return true;
        }
        return false;
    }

    public function setMESSAGE($message) {
        if ($message !== null && $message !== '') {
            $this->message = $message;
            return true;
        }
        return false;
    }

    public function setHotmail() {
        $this->secure = "tls";
        $this->host = "smtp.live.com";
        $this->port = 25;
    }

    public function setGmail() {
        $this->secure = "tls";
        $this->host = "smtp.gmail.com";
        $this->port = 587;
    }

    public function setYahoo() {
        $this->secure = "ssl";
        $this->host = "smtp.mail.yahoo.com";
        $this->port = 465;
    }

    public function showSMTPDebug() {
        if ($this->smtp !== null) {
            $this->smtp->SMTPDebug = 4;
        }
    }

    public function getErrorInfo() {
        if ($this->smtp !== null) {
            $this->smtp->ErrorInfo;
        }
    }
    
    public function getAllAddress() {
        if ($this->smtp !== null) {
            $this->smtp->getAllRecipientAddresses();
        }
    }

    public function prepareSMPT($server = 'Gmail') {
        $this->smtp->isSMTP();
        $this->smtp->SMTPAuth = true;
        if ($server === 'Gmail') {
            $this->setGmail();
        }
        if ($server === 'Hotmail' || $server === 'Outlook') {
            $this->setHotmail();
        }
        if ($server === 'Yahoo') {
            $this->setYahoo();
        }
        $this->smtp->SMTPDebug = 0;
        $this->smtp->SMTPAuth = true;
        $this->smtp->SMTPSecure = $this->secure;
        $this->smtp->Host = $this->host;
        $this->smtp->Port = $this->port;
        $this->smtp->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );


        if ($this->smtp !== null && $this->host !== null && $this->port !== null && $this->secure) {
            $this->prepared = true;
        }
        return $this->prepared;
    }

    public function isPrepared() {
        return $this->prepared;
    }

    public function txtMAIL($message = null) {
        $this->setMESSAGE($message);
        if ($this->from !== null && $this->subject !== null && $this->to !== null && $this->message !== null) {
            $this->headers = 'From:' . $this->from;
            $result = mail($this->to, $this->subject, $this->message, $this->headers);
            return $result;
        }
        return false;
    }

    public function htmlMAIL($html = null) {
        $this->setMESSAGE($html);
        if ($this->from !== null && $this->subject !== null && $this->to !== null && $this->message !== null) {
            $this->headers = 'MIME-Version: 1.0' . "\r\n";
            $this->headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $this->headers .= 'From: EMAIL <' . $this->from . '>' . "\r\n";
            $result = mail($this->to, $this->subject, $this->message, $this->headers);
            return $result;
        }
        return false;
    }

    public function sendMailSMTP($message = null) {
        $this->setMESSAGE($message);
        if ($this->from !== null && $this->subject !== null && $this->to !== null && $this->message !== null) {
            $this->emailcount = 0;
            $this->smtp->setFrom($this->username);
            $this->smtp->Username = $this->username;
            $this->smtp->Password = $this->password;
            if (is_array($this->to) && count($this->to) > 0 && !is_array($this->to[0])) {
                foreach ($this->to as $row => $value) {
                    if ($value !== null && $value !== '') {
                        $this->smtp->addAddress($value);
                        $this->emailcount++;
                    }
                }
            }
            if (!is_array($this->to)) {
                $this->smtp->addAddress($this->to);
            }
            $this->smtp->Subject = $this->subject;
            $this->smtp->isHTML();
            $this->smtp->Body = $this->message;
            $result = false;
            try {
                $result = $this->smtp->send();
            } catch (Exception $e) {
                echo 'Error al Enviar';
            }
            return $result;
        }
        return false;
    }

}
