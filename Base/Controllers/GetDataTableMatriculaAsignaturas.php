<?php

ob_start();
include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$idmatricula = null;
$idestudiante = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    if (isset($_POST['findby']) && $_POST['findby'] == 'id_matricula' && isset($_POST['findbyvalue']) && $_POST['findbyvalue'] !== '') {
        $idmatricula= $_POST['findbyvalue'];
    }
    if (isset($_POST['findby']) && $_POST['findby'] == 'id_estudiante' && isset($_POST['findbyvalue']) && $_POST['findbyvalue'] !== '') {
        $idestudiante= $_POST['findbyvalue'];
    }
    echo $bc->getAsignaturasMatriculadas($session->getEnterpriseID(), $idmatricula, $idestudiante);
    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>