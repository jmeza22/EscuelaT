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

    $idactividad = null;
    if (isset($arraywhere['id_actividad'])) {
        $idactividad = $arraywhere['id_actividad'];
    }

    $idestudiante = null;
    if (isset($arraywhere['id_estudiante'])) {
        $idestudiante = $arraywhere['id_estudiante'];
    } else {
        if ($session->getUserType() === 'Student') {
            $idestudiante = $session->getUserID();
        }
    }

    $idsolucion = null;
    if (isset($arraywhere['id_solucion'])) {
        $idsolucion = $arraywhere['id_solucion'];
    }

    echo $bc->getSolucionesActividadesVirtuales($idactividad, $idestudiante, $idsolucion);
    $bc->disconnect();
    $bc = null;
}
?>