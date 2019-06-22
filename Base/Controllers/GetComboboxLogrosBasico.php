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
    $bc->setModel('LogrosAsignaturasApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colvalue = "id_logro";
    $colname = "id_logro";
    $othervalue = "descripcion_logro";
    $arraywhere = $bc->parseFindByToArray($_POST);
    $arraywhere['status_logo'] = '1';
    $arraywhere['tipo_logo'] = 'DB';
    $arraywhere['id_escuela'] = ''.$session->getEnterpriseID();
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, null, $arraywhere);
    $bc->disconnect();
}
ob_end_flush();
?>