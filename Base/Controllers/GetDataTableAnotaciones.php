<?php


include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $arraywhere = $bc->parseFindByToArray($_POST);
    $idestudiante = null;
    if (isset($arraywhere['id_estudiante'])) {
        $idestudiante = $arraywhere['id_estudiante'];
    }
    echo $bc->getAnotaciones($idestudiante);
    $bc->disconnect();
    $bc = null;
}

?>