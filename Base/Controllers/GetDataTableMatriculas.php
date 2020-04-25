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
    
    $idsede = null;
    if (isset($arraywhere['id_sede'])) {
        $idsede = $arraywhere['id_sede'];
    }

    $idprograma = null;
    if (isset($arraywhere['id_programa'])) {
        $idprograma = $arraywhere['id_programa'];
    }
    
    $idplanestudio = null;
    if (isset($arraywhere['id_planestudio'])) {
        $idplanestudio = $arraywhere['id_planestudio'];
    }
    
    $grado = null;
    if (isset($arraywhere['numgrado_programa'])) {
        $grado = $arraywhere['numgrado_programa'];
    }

    $idgrupo = null;
    if (isset($arraywhere['id_grupo'])) {
        $idgrupo = $arraywhere['id_grupo'];
    }

    $idperiodo = null;
    if (isset($arraywhere['id_periodo'])) {
        $idperiodo = $arraywhere['id_periodo'];
    } else {
        $idperiodo = $variables->getIdPeriodoAnual();
    }
    
    $idestudiante = null;
    if (isset($arraywhere['id_estudiante'])) {
        $idestudiante = $arraywhere['id_estudiante'];
    }

    $fecha = null;
    if (isset($arraywhere['fecha_matricula'])) {
        $fecha = $arraywhere['fecha_matricula'];
    }
    echo $bc->getMatriculas($session->getEnterpriseID(), $idsede, null, $idprograma, $idplanestudio, $grado, $idgrupo, $idperiodo, $idestudiante, null, $fecha);
    $bc->disconnect();
    $bc = null;
}
?>