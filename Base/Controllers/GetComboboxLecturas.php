<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session=new SessionManager();
$bc = null;
if ($_POST != null && isset($_POST)) {
    $bc = new ReportsBank();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('LecturasApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colname = "titulo_lectura";
    $colvalue = "id_lectura";
    $othervalue = "id_escuela";
    $arraywhere = $bc->parseFindByToArray($_POST);
    $arraywhere['status_lectura'] = '1';
    $arraywhere['id_escuela'] = ''.$session->getEnterpriseID();
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, null, $arraywhere);
    $bc->disconnect();
}
?>