<?php

ob_start();
include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$where = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('PlanEstudioDetalleApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colvalue = "numgrado_programa";
    $colname = "CONCAT(numgrado_programa,'°')";
    $othervalue = "id_escuela";
    $arraywhere = $bc->parseFindByToArray($_POST);
    $arraywhere['status_planestudiodetalle'] = '1';
    $arraywhere['id_escuela'] = '' . $session->getEnterpriseID();
    $groupby = 'numgrado_programa';
    $orderby = 'CAST(numgrado_programa AS DECIMAL)';
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, null, $arraywhere, $groupby, $orderby);
    $bc->disconnect();
}
ob_end_flush();
?>