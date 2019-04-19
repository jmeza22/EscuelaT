<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$where = null;
if ($session->hasLogin() && $_REQUEST != null && isset($_REQUEST)) {
    $bc = new BaseController();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('AsignaturasApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colvalue = 'id_asignatura';
    $colname = 'nombre_asignatura';
    $othervalue = 'id_escuela';
    $where = 'status_asignatura=1 and id_escuela=' . $session->getEnterpriseID() . ' ';
    if (
            isset($_REQUEST['findby']) && strcmp($_REQUEST['findby'], 'id_planestudio') === 0 
            && isset($_REQUEST['findby2']) && strcmp($_REQUEST['findby2'], 'numgrado_programa') === 0 
            && isset($_REQUEST['findbyvalue']) && strcmp($_REQUEST['findbyvalue'], '') !== 0 
            && isset($_REQUEST['findbyvalue2']) && strcmp($_REQUEST['findbyvalue2'], '') !== 0
            ){
        $where = $where . "  and id_asignatura IN (SELECT PED.id_asignatura FROM PlanEstudioDetalleApp PED WHERE PED.id_planestudio=" . $_REQUEST['findbyvalue'] . " and PED.numgrado_programa=" . $_REQUEST['findbyvalue2'] . " and PED.status_planestudiodetalle = 1 GROUP BY PED.id_asignatura)";
    }
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, $where);
    $bc->disconnect();
}
ob_end_flush();
?>