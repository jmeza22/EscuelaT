<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1)) {
    $bc = new BaseController();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('PersonasApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colname = " UPPER(concat(apellido1_persona,' ', apellido2_persona,' ', nombre1_persona,' ', nombre2_persona)) ";
    $colvalue = 'id_persona';
    $othervalue = 'documento_persona';
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, ' status_persona=1');
    $bc->disconnect();
}else{
    echo 'null';
}
ob_end_flush();
?>