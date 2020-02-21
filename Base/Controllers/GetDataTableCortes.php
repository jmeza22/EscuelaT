<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$bc = null;
$idperiodo = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $arraywhere = $bc->parseFindByToArray($_POST);
    if (isset($arraywhere['id_periodo'])) {
        $idperiodo = $arraywhere['id_periodo'];
    } else {
        $idperiodo = $variables->getIdPeriodoAnual();
    }
    echo $bc->getCortesPeriodos($session->getEnterpriseID(), $idperiodo);
    $bc->disconnect();
    $bc = null;
}
?>