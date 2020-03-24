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
    } else {
        $idperiodo = $variables->getIdPeriodoAnual();
    }

    $grado = null;
    if (isset($arraywhere['numgrado_programa'])) {
        $grado = $arraywhere['numgrado_programa'];
    }

    $idgrupo = null;
    if (isset($arraywhere['id_grupo'])) {
        $idgrupo = $arraywhere['id_grupo'];
    }

    echo $bc->getContactosEstudiantesMatriculas($session->getEnterpriseID(), $idprograma, $grado, $idgrupo, $idperiodo, $idestudiante);
    $bc->disconnect();
    $bc = null;
}
?>