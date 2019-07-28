<?php

ob_start();
include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$idestudiante = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    if (isset($_POST['findby']) && $_POST['findby'] == 'id_estudiante' && isset($_POST['findbyvalue']) && $_POST['findbyvalue'] !== '') {
        $idestudiante = $_POST['findbyvalue'];
    }
    echo $bc->getCitaciones($idestudiante);
    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>