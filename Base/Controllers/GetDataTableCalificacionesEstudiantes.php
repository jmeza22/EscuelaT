<?php

ob_start();
include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$idmatricula = null;
if ($session->hasLogin() && isset($_POST) && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1)) {
    $bc = new ReportsBank();
    $bc->connect();
    if (isset($_REQUEST['findby']) && isset($_REQUEST['findbyvalue']) && strcmp($_REQUEST['findby'], '') !== 0 && strcmp($_REQUEST['findbyvalue'], '') !== 0 && strcmp($_REQUEST['findby'], 'id_matricula') === 0) {
        $idmatricula = $_REQUEST['findbyvalue'];
    }
    echo $bc->getCalificaciones($session->getEnterpriseID(), NULL, null, null, null, null, null, null, $idmatricula, null);
    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>