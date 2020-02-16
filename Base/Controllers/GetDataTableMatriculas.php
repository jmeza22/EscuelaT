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

    $idprograma = null;
    if (isset($arraywhere['id_programa'])) {
        $idprograma = $arraywhere['id_programa'];
    }

    $idperiodo = null;
    if (isset($arraywhere['id_periodo'])) {
        $idperiodo = $arraywhere['id_periodo'];
    }

    $grado = null;
    if (isset($arraywhere['numgrado_programa'])) {
        $grado = $arraywhere['numgrado_programa'];
    }

    $idsede = null;
    if (isset($arraywhere['id_sede'])) {
        $idsede = $arraywhere['id_sede'];
    }

    $idgrupo = null;
    if (isset($arraywhere['id_grupo'])) {
        $idgrupo = $arraywhere['id_grupo'];
    }

    $fecha = null;
    if (isset($arraywhere['fecha_matricula'])) {
        $fecha = $arraywhere['fecha_matricula'];
    }
    echo $bc->getMatriculas($session->getEnterpriseID(), $idsede, null, $idprograma, null, $grado, $idgrupo, $idperiodo, $idestudiante, null, $fecha);
    $bc->disconnect();
    $bc = null;
}
?>