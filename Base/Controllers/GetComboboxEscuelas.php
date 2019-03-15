<?php

ob_start();
include_once 'Libraries/Controllers.php';
if ($_POST != null && isset($_POST)) {
    $bc = new BaseController();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('EscuelasApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colname = 'nombre_escuela';
    $colvalue = 'id_escuela';
    $othervalue = 'id_escuela';
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, 'status_escuela=1');
    $bc->disconnect();
}
ob_end_flush();
?>