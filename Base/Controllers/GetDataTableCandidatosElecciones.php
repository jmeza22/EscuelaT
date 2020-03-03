<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$bc = null;
if ($session->hasLogin()) {
    $bc = new ReportsBank();
    $bc->connect();
    echo $bc->getCandidatosElecciones($session->getEnterpriseID(), null, null);
    $bc->disconnect();
    $bc = null;
}
?>