<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$bc = null;
if ($session->hasLogin() && ($session->getManagement() == 1 || $session->getAdmin() == 1 || $session->getSuperAdmin() == 1)) {
    $bc = new ReportsBank();
    $bc->connect();
    $arraywhere = $bc->parseFindByToArray($_POST);
    $ideleccion = null;
    if (isset($arraywhere['id_eleccion'])) {
        $ideleccion = $arraywhere['id_eleccion'];
    }
    $idcargo = null;
    if (isset($arraywhere['id_cargo'])) {
        $idcargo = $arraywhere['id_cargo'];
    }
    echo $bc->getConteoVotosElecciones($session->getEnterpriseID(), $ideleccion, $idcargo, null);
    $bc->disconnect();
    $bc = null;
}
?>