<?php

ob_start();
include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$where = null;
if ($session->hasLogin() && $_POST != null && isset($_POST)) {
    $bc = new ReportsBank();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('PlanEstudiosApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colvalue = "id_planestudio";
    $colname = "descripcion_planestudio";
    $othervalue = "id_programa";
    $arraywhere = $bc->parseFindByToArray($_POST);
    $arraywhere['status_planestudio'] = '1';
    $arraywhere['id_escuela'] = ''.$session->getEnterpriseID();
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, null, $arraywhere);
    $bc->disconnect();
}
ob_end_flush();
?>