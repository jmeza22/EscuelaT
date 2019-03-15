<?php

class DataSettings {

    private $Settings = null;
    private $Articulo = null;
    private $xmlURL = "XML/Settings.xml";
    private $xmlEmpty = '<?xml version="1.0" standalone="yes"?><settings></settings>';
    private $empty = '<setting><code state=""/><text/><enterprise/><welcome/><local/><remote/><lastdate/><hostdb/><portdb/><userdb/><passworddb/><namedb/></setting>';
    private $message = "";

    public function __construct($url = null) {
        if ($url != null) {
            $this->xmlURL=$url;
        }
        try {
            $content = $this->loadfile();
            if (isset($content) && $content != null) {
                $this->Settings = new SimpleXMLElement($content);
            } else {
                $this->Settings = new SimpleXMLElement($this->xmlEmpty);
            }
        } catch (Exception $e) {
            $this->Settings = new SimpleXMLElement($this->xmlEmpty);
        }
    }

    private function loadfile() {
        try {
            $content = file_get_contents($this->xmlURL);

            return $content;
        } catch (Exception $e) {
            exit;
        }
        return null;
    }

    private function savefile() {
        try {
            unlink($this->xmlURL);
            $fp = fopen($this->xmlURL, "w");
            fputs($fp, $this->Settings->asXML());
            fclose($fp);
            return true;
        } catch (Exception $e) {
            exit;
        }
        return false;
    }

    private function save() {
        $auxXML = $this->Settings->asXML();
        $auxXML = str_replace("<settings><setting>", "<settings>\n<setting>", $auxXML);
        $auxXML = str_replace("</setting></settings>", "</setting>\n</settings>", $auxXML);
        $auxXML = str_replace("</setting><setting>", "</setting>\n<setting>", $auxXML);
        $auxXML = str_replace("\n\n", "\n", $auxXML);
        $auxXML = str_replace($this->xmlEmpty, "", $auxXML);
        $this->Settings = new SimpleXMLElement($auxXML);
        $this->Settings->asXML($this->xmlURL);
    }

    public function getJSON() {
        $fileContents = $this->Settings->asXML();
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        $fileContents = trim(str_replace('"', "'", $fileContents));
        $simpleXml = simplexml_load_string($fileContents);
        $json = json_encode($simpleXml);
        return $json;
    }

    public function cleanMessage() {
        $this->message = "";
    }

    public function getMessage() {
        return $this->message;
    }

    public function getLogJSON() {
        $array = array();
        $array['message'] = $this->getMessage();
        $array['error'] = '';
        $array['state'] = 0;
        $array['data'] = null;
        $array = json_encode($array);
        return $array;
    }

    protected function existsCode($code) {
        foreach ($this->Settings->setting as $art) {
            if ($art->code == $code) {
                return true;
            }
        }
        return false;
    }

    public function getAllSettings() {
        return $this->Settings->setting;
    }

    public function getAllSettingsJSON() {
        return json_decode($this->getAllSettings());
    }

    public function getSetting($code) {
        foreach ($this->Settings->setting as $sett) {
            if ($sett->code == $code) {
                return $sett;
            }
        }
        return null;
    }

    public function getSettingIndex($code) {
        for ($i = 0; $i < count($this->Settings->setting); $i++) {
            $sett = $this->Settings->setting[$i];
            if ($sett->code == $code) {
                return $i;
            }
        }
        return null;
    }

    public function getSettingJSON($code) {
        foreach ($this->Settings->setting as $sett) {
            if ($sett->code == $code) {
                $array = array();
                $data = array();
                $array['message'] = '';
                $array['error'] = '';
                $array['state'] = 0;
                $data['setting'] = $sett;
                $array['data'] = json_encode($data);
                $array = json_encode($array);
                return $array;
            }
        }
        return null;
    }

    public function getCode($index) {
        return $this->Settings->setting[$index]->code;
    }

    public function getText($index) {
        return $this->Settings->setting[$index]->text;
    }

    public function getEnterprise($index) {
        return $this->Settings->setting[$index]->enterprise;
    }

    public function getWelcome($index) {
        return $this->Settings->setting[$index]->welcome;
    }

    public function getLocal($index) {
        return $this->Settings->setting[$index]->local;
    }

    public function getRemote($index) {
        return $this->Settings->setting[$index]->remote;
    }

    public function getLastDate($index) {
        return $this->Settings->setting[$index]->lastdate;
    }

    public function getHostDB($index) {
        return $this->Settings->setting[$index]->hostdb;
    }

    public function getPortDB($index) {
        return $this->Settings->setting[$index]->portdb;
    }

    public function getUserDB($index) {
        return $this->Settings->setting[$index]->userdb;
    }

    public function getPasswordDB($index) {
        return $this->Settings->setting[$index]->passworddb;
    }

    public function getNameDB($index) {
        return $this->Settings->setting[$index]->namedb;
    }

    public function AddSetting($mycode, $mytext, $myenterprise, $mywelcome, $mylocal, $myremote, $mylastdate, $myhostdb, $myportdb, $myuserdb, $mypassworddb, $mynamedb) {
        $this->cleanMessage();
        if (!$this->existsCode($mycode)) {
            $newsetting = $this->Settings->addChild('setting');
            $code = $newsetting->addChild('code', $mycode);
            $code->addAttribute('state', 1);
            $text = $newsetting->addChild('text', $mytext);
            $enterprise = $newsetting->addChild('enterprise', $myenterprise);
            $welcome = $newsetting->addChild('welcome', $mywelcome);
            $local = $newsetting->addChild('local', $mylocal);
            $remote = $newsetting->addChild('remote', $myremote);
            $lastdate = $newsetting->addChild('lastdate', $mylastdate);
            $hostdb = $newsetting->addChild('hostdb', $myhostdb);
            $portdb = $newsetting->addChild('portdb', $myportdb);
            $userdb = $newsetting->addChild('userdb', $myuserdb);
            $passworddb = $newsetting->addChild('passworddb', $mypassworddb);
            $namedb = $newsetting->addChild('namedb', $mynamedb);
            if ($this->Settings->asXML()) {
                $this->save();
                $this->message = 'Registro Exitoso!';
                return true;
            } else {
                $this->message = 'Hubo un error!';
            }
        } else {
            $this->message = 'Codigo ya existe!';
        }
        return false;
    }

    public function EditSetting($mycode, $mytext, $myenterprise, $mywelcome, $mylocal, $myremote, $mylastdate, $myhostdb, $myportdb, $myuserdb, $mypassworddb, $mynamedb) {
        $this->cleanMessage();
        if ($this->existsCode($mycode)) {
            $thesetting = $this->getSetting($mycode);
            if (isset($thesetting) && $thesetting != null) {
                $thesetting->text = $mytext;
                $thesetting->enterprise = $myenterprise;
                $thesetting->welcome = $mywelcome;
                $thesetting->local = $mylocal;
                $thesetting->remote = $myremote;
                $thesetting->lastdate = $mylastdate;
                $thesetting->hostdb = $myhostdb;
                $thesetting->portdb = $myportdb;
                $thesetting->userdb = $myuserdb;
                $thesetting->passworddb = $mypassworddb;
                $thesetting->namedb = $mynamedb;
            }
            if ($this->Settings->asXML($this->xmlURL)) {
                $this->message = 'Actualizacion Exitosa!';
                return true;
            } else {
                $this->message = 'Hubo un error!';
            }
        } else {
            $this->message = 'No existe el code!';
        }
    }

    public function LogicDeleteSetting($mycode) {
        $this->cleanMessage();
        if ($this->existeCodigo($mycode)) {
            $thesetting = $this->getSetting($mycode);
            $thesetting->code['state'] = 0;
            if ($this->Settings->asXML($this->xmlURL)) {
                $this->message = 'Eliminacion Exitosa!';
                return true;
            } else {
                $this->message = 'Hubo un error!';
            }
        }
    }

    public function DeleteSetting($mycode) {
        $this->cleanMessage();
        if ($this->existsCode($mycode)) {
            $thesetting = $this->getSetting($mycode);
            $thesetting->code = "";
            $thesetting->code['state'] = "";
            $thesetting->text = "";
            $thesetting->enterprise = "";
            $thesetting->welcome = "";
            $thesetting->local = "";
            $thesetting->remote = "";
            $thesetting->lastdate = "";
            $thesetting->hostdb = "";
            $thesetting->portdb = "";
            $thesetting->userdb = "";
            $thesetting->passworddb = "";
            $thesetting->namedb = "";
            $auxXML = $this->Settings->asXML();
            $auxXML = str_replace($this->empty, null, $auxXML);
            $this->Settings = new SimpleXMLElement($auxXML);
            if ($this->Settings->asXML($this->xmlURL)) {
                $this->message = 'Eliminacion Exitosa!';
                return true;
            } else {
                $this->message = 'Hubo un error!';
            }
        }
    }

}
