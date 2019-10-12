<?php

ob_start();
include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $idestudiante=null;
    $arraywhere = $bc->parseFindByToArray($_POST);
    if(isset($arraywhere['id_estudiante'])){
        $idestudiante=$arraywhere['id_estudiante'];
    }
    echo $bc->getMatriculas($session->getEnterpriseID(), null, null, null, null, null, null, null, $idestudiante);
    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>