<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$idcuestionario = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $arraywhere = $bc->parseFindByToArray($_POST);
    if (isset($arraywhere['id_cuestionario'])) {
        $idcuestionario = $arraywhere['id_cuestionario'];
    }
    echo $bc->getCuestionarios($session->getEnterpriseID(), $idcuestionario);
    $bc->disconnect();
    $bc = null;
}
?>