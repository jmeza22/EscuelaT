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

    public function setUser($user) {
        if ($user !== null && $user !== '') {
            $this->user = $user;
            return true;
        }
        return false;
    }

    public function setPassword($password) {
        if ($password !== null && $password !== '') {
            $this->password = $password;
            return true;
        }
        return false;
    }

    public function setFrom($from) {
        if ($from !== null && $from !== '') {
            $this->from = $from;
            return true;
        }
        return false;
    }

    public function SendWauSMS($to, $message) {
        $arrayTO = Array();
        if (is_array($to) && count($to) > 0 && !is_array($to[0])) {
            $arrayTO = $to;
        } else {
            if (!is_array($to) && is_numeric($to)) {
                $arrayTO[0] = $to;
            }
        }
        if (is_array($arrayTO) && count($arrayTO) > 0 && $message !== null && $message !== '') {
            $post['to'] = $arrayTO;
            $post['text'] = $message;
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
            return $result;
        }
        return null;
    }

}
