<?php


include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
if ($_REQUEST != null && isset($_REQUEST)) {
    $bc = new ReportsBank();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('EscuelasApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colname = "nombre_escuela";
    $colvalue = "id_escuela";
    $othervalue = "id_escuela";
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, 'status_escuela=1');
    $bc->disconnect();
    
}

?>