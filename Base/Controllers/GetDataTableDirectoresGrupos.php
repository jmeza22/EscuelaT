<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    echo $bc->getDirectoresGrupos($session->getEnterpriseID(), null, $variables->getIdPeriodoAnual());
    $bc->disconnect();
    $bc = null;
}
?>