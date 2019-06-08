<?php

ob_start();
include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new BancoReportes();
    $bc->connect();
    echo $bc->getAsignaturas($session->getEnterpriseID());
    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>