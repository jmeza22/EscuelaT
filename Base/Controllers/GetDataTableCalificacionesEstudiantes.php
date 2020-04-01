<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$bc = null;
if ($session->hasLogin() && isset($_POST)) {
    $bc = new ReportsBank();
    $bc->connect();
    $arraywhere = $bc->parseFindByToArray($_POST);

    $idmatricula = null;
    if (isset($arraywhere['id_matricula'])) {
        $idmatricula = $arraywhere['id_matricula'];
    }

    $idestudiante = null;
    if (isset($arraywhere['id_estudiante'])) {
        $idestudiante = $arraywhere['id_estudiante'];
    }else{
        if($session->getUserType()==='Student'){
            $idestudiante = $session->getUserID();
        }
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

    $idgrupo = null;
    if (isset($arraywhere['id_grupo'])) {
        $idgrupo = $arraywhere['id_grupo'];
    }
    
    echo $bc->getCalificaciones($session->getEnterpriseID(), null, null, $idprograma, null, $grado, $idgrupo, $idperiodo, $idestudiante, $idmatricula);
    $bc->disconnect();
    $bc = null;
}
?>