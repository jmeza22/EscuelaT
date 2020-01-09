<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();

    $arraywhere = $bc->parseFindByToArray($_POST);
    if (!isset($arraywhere['id_matricula'])) {
        $arraywhere['id_matricula'] = null;
    }
    if (!isset($arraywhere['id_estudiante'])) {
        $arraywhere['id_estudiante'] = null;
    }
    if (!isset($arraywhere['id_programa'])) {
        $arraywhere['id_programa'] = null;
    }
    if (!isset($arraywhere['id_asignatura'])) {
        $arraywhere['id_asignatura'] = null;
    }
    if (!isset($arraywhere['id_periodo'])) {
        $arraywhere['id_periodo'] = null;
    }
    if (!isset($arraywhere['numgrado_prograna'])) {
        $arraywhere['numgrado_prograna'] = null;
    }
    if (!isset($arraywhere['id_grupo'])) {
        $arraywhere['id_grupo'] = null;
    }
    echo $bc->getAsignaturasMatriculadas($session->getEnterpriseID(), $arraywhere['id_matricula'], $arraywhere['id_estudiante'], $arraywhere['id_programa'], $arraywhere['id_asignatura'], $arraywhere['id_periodo'], $arraywhere['numgrado_programa'], $arraywhere['id_grupo']);
    $bc->disconnect();
    $bc = null;
}
?>