<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$where = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('AsignaturasApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colvalue = "id_asignatura";
    $colname = "nombre_asignatura";
    $othervalue = "(hteoricas_asignatura + hpracticas_asignatura)";
    $where = "status_asignatura=1 and id_escuela='" . $session->getEnterpriseID() . "' ";
    $arraywhere = $bc->parseFindByToArray($_POST);
    if (
            isset($arraywhere['id_planestudio']) && isset($arraywhere['numgrado_programa'])
    ) {
        $where = $where . "  and id_asignatura IN (SELECT PED.id_asignatura FROM PlanEstudioDetalleApp PED WHERE PED.id_planestudio='" . $arraywhere['id_planestudio'] . "' and PED.numgrado_programa=" . $arraywhere['numgrado_programa'] . " and PED.status_planestudiodetalle = 1 GROUP BY PED.id_asignatura)";
    }
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, $where);
    $bc->disconnect();
}
?>