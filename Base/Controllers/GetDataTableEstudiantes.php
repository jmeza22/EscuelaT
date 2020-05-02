<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$bc = null;
if ($session->hasLogin() && isset($_POST) && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1)) {
    $bc = new ReportsBank();
    $bc->connect();
    $arraywhere = $bc->parseFindByToArray($_POST);

    $idprograma = null;
    if (isset($arraywhere['id_programa'])) {
        $idprograma = $arraywhere['id_programa'];
    }

    $idplanestudio = null;
    if (isset($arraywhere['id_planestudio'])) {
        $idplanestudio = $arraywhere['id_planestudio'];
    }

    $grado = null;
    if (isset($arraywhere['numgrado_programa']) && $arraywhere['numgrado_programa'] !== '') {
        $grado = $arraywhere['numgrado_programa'];
    }

    $idgrupo = null;
    if (isset($arraywhere['id_grupo']) && $arraywhere['id_grupo'] !== '') {
        $idgrupo = $arraywhere['id_grupo'];
    }

    $idperiodo = null;
    if (isset($arraywhere['id_periodo'])) {
        $idperiodo = $arraywhere['id_periodo'];
    } else {
        $idperiodo = $variables->getIdPeriodoAnual();
    }
    //print_r($arraywhere);
    echo $bc->getEstudiantesMatriculas($session->getEnterpriseID(), null, null, $idprograma, $idplanestudio, $grado, $idgrupo, $idperiodo, null);
    $bc->disconnect();
    $bc = null;
}
?>