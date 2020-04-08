<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SendSMS
 *
 * @author JOSE MEZA
 */
class SendSMS {

    private $user = null;
    private $password = null;
    private $from = 'SMS';
    private $to = null;
    private $message = null;

    public function setUSERNAME($user) {
        if ($user !== null && $user !== '') {
            $this->user = $user;
            return true;
        }
        return false;
    }

    public function setPASSWORD($password) {
        if ($password !== null && $password !== '') {
            $this->password = $password;
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
        if (is_array($to) && count($to) > 0 && !is_array($to[0])) {
            $arrayTO = array();
            foreach ($to as $row => $value) {
                if ($value !== null && $value !== '') {
                    $arrayTO[] = $value;
                }
            }
            if (count($arrayTO) > 0) {
                $this->to = $arrayTO;
                return true;
            }
        }
        return false;
    }

    public function setMSISDN($to) {
        if (is_array($to) && count($to) > 0 && !is_array($to[0])) {
            $arrayTO = array();
            $i = 0;
            foreach ($to as $row => $value) {
                if ($value !== null && $value !== '') {
                    $arrayTO[]['msisdn'] = $value;
                    $i++;
                }
            }
            if (count($arrayTO) > 0) {
                $this->to = $arrayTO;
                return true;
            }
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

    public function getTO() {
        return $this->to;
    }

    public function SendWauSMS($message) {
        $this->setMESSAGE($message);
        if (is_array($this->to) && count($this->to) > 0 && $this->from !== null && $this->from !== '' && $this->message !== null && $this->message !== '') {
            $post['to'] = $this->to;
            $post['text'] = $this->message;
            $post['from'] = $this->from;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://dashboard.wausms.com/Api/rest/message");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Accept: application/json",
                "Authorization: Basic " . base64_encode($this->user . ":" . $this->password)));
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
        return null;
    }

    public function SendLabsMobile($message) {
        $this->setMESSAGE($message);
        if (is_array($this->to) && count($this->to) > 0 && $this->message !== null && $this->message !== '') {
            $this->setMSISDN($this->to);
            $post['message'] = $this->message;
            $post['tpoa'] = "Sender";
            $post['recipient'] = $this->to;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.labsmobile.com/json/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($post),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic " . base64_encode($this->user . ":" . $this->password),
                    "Cache-Control: no-cache",
                    "Content-Type: application/json"
                ),
            ));

            $result = curl_exec($curl);
            $err = curl_error($curl);
            if($err){
                echo $err;
            }
            curl_close($curl);
            return $result;
        }
        return null;
    }

}
