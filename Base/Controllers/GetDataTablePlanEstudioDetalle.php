<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$idprograma = null;
$idplanestudio = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $arraywhere = $bc->parseFindByToArray($_POST);
    if (isset($arraywhere['id_programa'])) {
        $idprograma = $arraywhere['id_programa'];
    }
    if (isset($arraywhere['id_planestudio'])) {
    $idplanestudio = $arraywhere['id_planestudio'];
    }
    echo $bc->getPlanEstudioDetalle($session->getEnterpriseID(), $idprograma, null, $idplanestudio);
    $bc->disconnect();
    $bc = null;
}
?>