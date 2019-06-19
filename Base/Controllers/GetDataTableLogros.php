<?php

ob_start();
include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$idasignatura = null;
$numgrado = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    if (isset($_POST['findby']) && $_POST['findby'] == 'id_asignatura' && isset($_POST['findbyvalue']) && $_POST['findbyvalue'] !== '') {
        $idasignatura = $_POST['findbyvalue'];
    }
    if (isset($_POST['findby2']) && $_POST['findby2'] == 'numgrado_programa' && isset($_POST['findbyvalue2']) && $_POST['findbyvalue2'] !== '') {
        $numgrado = $_POST['findbyvalue2'];
    }
    echo $bc->getLogrosAsignaturas($session->getEnterpriseID(), $idasignatura, $numgrado);
    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>