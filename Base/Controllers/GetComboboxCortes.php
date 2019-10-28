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
    $bc->setModel('CortesPeriodosApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colname = 'numero_corte';
    $colvalue = 'id_corte';
    $othervalue = 'estado_corte';
    $arraywhere = $bc->parseFindByToArray($_POST);
    $arraywhere['status_corte'] = '1';
    $arraywhere['estado_corte'] = 'A';
    $arraywhere['id_escuela'] = ''.$session->getEnterpriseID();
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, null, $arraywhere);
    $bc->disconnect();
}

?>