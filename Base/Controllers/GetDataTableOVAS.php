<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $arraywhere = $bc->parseFindByToArray($_POST);
    
    $idasignatura = null;
    if (isset($arraywhere['id_asignatura'])) {
        $idasignatura = $arraywhere['id_asignatura'];
    }

    $idautor = null;
    if (isset($arraywhere['id_autor'])) {
        $idautor = $arraywhere['id_autor'];
    }
    
    $grado = null;
    if (isset($arraywhere['numgrado_ova'])) {
        $grado = $arraywhere['numgrado_ova'];
    }
    echo $bc->getOVAS($bc->getEnterpriseID(), $idasignatura, $idautor, $grado);
    $bc->disconnect();
    $bc = null;
}
?>