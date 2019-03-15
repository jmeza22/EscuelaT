<?php

ob_start();
include_once 'SetsAndHeaders.php';
session_start();

class SessionManager {

    private $lock = true;
    private $timezone = "America/Bogota";
    private $website = null;
    private $tokenForm = null;
    private $time = 900;
    private $index_token = 'token';
    private $index_othertoken = 'othertoken';
    private $index_userid = 'userid';
    private $index_usertype = 'usertype';
    private $index_nickname = 'nickname';
    private $index_fullname = 'fullname';
    private $index_enterpriseid = 'enterpriseid';
    private $index_superadmin = 'superadmin';
    private $index_admin = 'admin';
    private $index_management = 'management';
    private $index_standard = 'standard';
    private $index_limited = 'limited';
    private $index_external = 'external';
    private $index_time = 'starttime';

    function __construct() {
        $this->setHost();
        $this->setTokenForm();
    }

    function setHost($host = null) {
        if ($host != null) {
            $this->website = $host;
        } else {
            $this->website = $_SERVER["SERVER_NAME"];
        }
    }

    function setTokenForm($token = null) {
        if ($token != null) {
            $this->tokenForm = $token;
        } else {
            if ($_REQUEST != null && isset($_REQUEST['token']) && $_REQUEST['token'] != null) {
                $this->tokenForm = $_REQUEST['token'];
            }
        }
    }

    function setUserPermissions($super = null, $admin = null, $management = null, $standard = null, $limited = null, $external = null) {
        $_SESSION[$this->index_superadmin] = $super;
        $_SESSION[$this->index_admin] = $admin;
        $_SESSION[$this->index_management] = $management;
        $_SESSION[$this->index_standard] = $standard;
        $_SESSION[$this->index_limited] = $limited;
        $_SESSION[$this->index_external] = $external;
    }

    function setUserIdForm($userid) {
        if ($userid != null) {
            $_SESSION[$this->index_userid] = $userid;
        }
    }

    function setUserTypeForm($usertype) {
        if ($usertype != null) {
            $_SESSION[$this->index_usertype] = $usertype;
        }
    }

    function setNicknameForm($nickname) {
        if ($nickname != null) {
            $_SESSION[$this->index_nickname] = $nickname;
        }
    }

    function setFullnameForm($fullname) {
        if ($fullname != null) {
            $_SESSION[$this->index_fullname] = $fullname;
        }
    }

    function setEnterpriseIdForm($id) {
        if ($id != null) {
            $_SESSION[$this->index_enterpriseid] = $id;
        }
    }

    function setTime($time) {
        if ($time != null && is_numeric($time)) {
            $this->time = $time;
        }
    }

    function getTimeZone() {
        return $this->timezone;
    }

    function getHost() {
        return $this->website;
    }

    function isSecurityOn() {
        return $this->lock;
    }

    function hasLogin() {
        if (isset($_SESSION[$this->index_userid])) {
            return true;
        } else {
            return false;
        }
    }

    function GenerateToken() {
        $_SESSION[$this->index_token] = md5(uniqid(rand() * 9204, true));
    }

    function GenerateTokenSingUp() {
        $_SESSION[$this->index_othertoken] = md5(uniqid(rand() * 2204, true));
    }

    function getToken() {
        if (isset($_SESSION[$this->index_token])) {
            return $_SESSION[$this->index_token];
        }
        return null;
    }

    function getTokenSingUp() {
        if (isset($_SESSION[$this->index_othertoken])) {
            return $_SESSION[$this->index_othertoken];
        }
        return null;
    }

    function CheckToken() {
        if (isset($this->tokenForm) && $this->tokenForm != NULL && $this->tokenForm != '' && isset($_SESSION[$this->index_token]) && $this->tokenForm == $_SESSION[$this->index_token]) {
            return true;
        }
        return false;
    }

    function CheckTokenSingUp() {
        if (isset($this->tokenForm) && $this->tokenForm != NULL && $this->tokenForm != '' && $this->getTokenSingUp() != '' && $this->tokenForm == $_SESSION['tokensingup']) {
            return true;
        }
        return false;
    }

    function CleanTokenSingUp() {
        $_SESSION['tokensingup'] = null;
    }

    function setLogin($iduser, $user, $type = null, $fullname = null, $identerprise = null, $super = null, $admin = null, $management = null, $standard = null, $limited = null, $external = null) {
        if ($iduser != null && $user != null) {
            $_SESSION[$this->index_time] = time();
            $_SESSION[$this->index_userid] = $iduser;
            $_SESSION[$this->index_nickname] = $user;
            $_SESSION[$this->index_usertype] = $type;
            $_SESSION[$this->index_fullname] = $fullname;
            $_SESSION[$this->index_enterpriseid] = $identerprise;
            $_SESSION[$this->index_superadmin] = $super;
            $_SESSION[$this->index_admin] = $admin;
            $_SESSION[$this->index_management] = $management;
            $_SESSION[$this->index_standard] = $standard;
            $_SESSION[$this->index_limited] = $limited;
            $_SESSION[$this->index_external] = $external;
            $this->GenerateToken();
            return true;
        } else {
            return false;
        }
    }

    function logout() {
        $_SESSION[$this->index_time] = null;
        $_SESSION[$this->index_userid] = null;
        $_SESSION[$this->index_nickname] = null;
        $_SESSION[$this->index_usertype] = null;
        $_SESSION[$this->index_enterpriseid] = null;
        $_SESSION[$this->index_superadmin] = null;
        $_SESSION[$this->index_admin] = null;
        $_SESSION[$this->index_management] = null;
        $_SESSION[$this->index_standard] = null;
        $_SESSION[$this->index_limited] = null;
        $_SESSION[$this->index_external] = null;
        $_SESSION[$this->index_token] = null;
        session_unset();
        session_destroy();
    }

    function TimeOff() {
        if (isset($_SESSION[$this->index_time])) {
            $timeoff = $_SESSION[$this->index_time] + $this->time;
            if (time() > $timeoff) {
                $this->logout();
                return true;
            } else {
                return false;
            }
        }
    }

    function setLastActionTime() {
        $_SESSION[$this->index_time] = time();
    }

    function getUserType() {
        if (isset($_SESSION[$this->index_usertype])) {
            return $_SESSION[$this->index_usertype];
        }
        return null;
    }

    function getUserID() {
        if (isset($_SESSION[$this->index_userid])) {
            return $_SESSION[$this->index_userid];
        }
        return null;
    }

    function getNickname() {
        if (isset($_SESSION[$this->index_nickname])) {
            return $_SESSION[$this->index_nickname];
        }
        return null;
    }

    function getFullname() {
        if (isset($_SESSION[$this->index_fullname])) {
            return $_SESSION[$this->index_fullname];
        }
        return null;
    }

    function getEnterpriseID() {
        if (isset($_SESSION[$this->index_enterpriseid])) {
            return $_SESSION[$this->index_enterpriseid];
        }
        return null;
    }

    function getSuperAdmin() {
        if (isset($_SESSION[$this->index_superadmin])) {
            return $_SESSION[$this->index_superadmin];
        }
        return null;
    }

    function getAdmin() {
        if (isset($_SESSION[$this->index_admin])) {
            return $_SESSION[$this->index_admin];
        }
        return null;
    }

    function getManagement() {
        if (isset($_SESSION[$this->index_management])) {
            return $_SESSION[$this->index_management];
        }
        return null;
    }

    function getStandard() {
        if (isset($_SESSION[$this->index_standard])) {
            return $_SESSION[$this->index_standard];
        }
        return null;
    }

    function getLimited() {
        if (isset($_SESSION[$this->index_limited])) {
            return $_SESSION[$this->index_limited];
        }
        return null;
    }

    function getExternal() {
        if (isset($_SESSION[$this->index_external])) {
            return $_SESSION[$this->index_external];
        }
        return null;
    }

    function getSessionStateJSON() {
        $array = array();
        $data = array();
        $array['message'] = 'You must be login.';
        $array['error'] = null;
        $array['data'] = null;
        $array['status'] = 0;
        $data['userid'] = null;
        $data['user'] = null;
        $data['userrole'] = null;
        $data['fullname'] = null;
        if ($this->hasLogin()) {
            $data['userid'] = $this->getUserID();
            $data['user'] = $this->getNickname();
            $data['userrole'] = $this->getUserType();
            $data['fullname'] = $this->getFullname();
            $array['message'] = 'You have a Active Session.';
            $array['status'] = 1;
        }
        $data = json_encode($data);
        $array['data'] = $data;
        $array = json_encode($array);
        return $array;
    }

    function AccessControl() {
        
    }

}

ob_end_flush();
?>