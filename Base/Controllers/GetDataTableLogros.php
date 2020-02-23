<?php


include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$idasignatura = null;
$numgrado = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $arraywhere = $bc->parseFindByToArray($_POST);
    if (isset($arraywhere['id_asignatura'])) {
        $idasignatura = $arraywhere['id_asignatura'];
    }
    if (isset($arraywhere['numgrado_programa'])) {
        $numgrado = $arraywhere['numgrado_programa'];
    }
    echo $bc->getLogrosAsignaturas($session->getEnterpriseID(), $idasignatura, $numgrado);
    $bc->disconnect();
    $bc = null;
}

?>