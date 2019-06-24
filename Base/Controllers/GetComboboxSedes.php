<?php

ob_start();
include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session=new SessionManager();
$bc = null;
$where = 'status_sede=1';
if ($_POST != null && isset($_POST)) {
    $bc = new ReportsBank();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('SedesApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colname = "CONCAT(id_sede,' ',nombre_sede)";
    $colvalue = "id_sede";
    $othervalue = "id_escuela";
    $arraywhere = $bc->parseFindByToArray($_POST);
    $arraywhere['status_sede'] = '1';
    $arraywhere['id_escuela'] = ''.$session->getEnterpriseID();
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, null, $arraywhere);
    $bc->disconnect();
}
ob_end_flush();
?>