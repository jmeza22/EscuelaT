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
    $idestudiante = null;

    $idprograma = null;
    if (isset($arraywhere['id_programa'])) {
        $idprograma = $arraywhere['id_programa'];
    }

    $idasignatura = null;
    if (isset($arraywhere['id_asignatura'])) {
        $idasignatura = $arraywhere['id_asignatura'];
    }

    $iddocente = null;
    if (isset($arraywhere['id_docente'])) {
        $iddocente = $arraywhere['id_docente'];
    } else {
        if ($session->getUserType() === 'Teacher') {
            $iddocente = $session->getUserID();
        }
    }

    $numgrado = null;
    if (isset($arraywhere['numgrado_programa'])) {
        $numgrado = $arraywhere['numgrado_programa'];
    }

    echo $bc->getActividadesVirtuales($session->getEnterpriseID(), $idprograma, $idasignatura, $iddocente, $numgrado, null);
    $bc->disconnect();
    $bc = null;
}
?>