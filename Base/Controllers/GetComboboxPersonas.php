<?php

ob_start();
include_once 'Libraries/Controllers.php';
if ($_POST != null && isset($_POST)) {
    $bc = new BaseController();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('PersonasApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colname = "concat(nombre1_persona,' ', apellido1_persona)";
    $colvalue = 'id_persona';
    $othervalue = 'documento_persona';
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, 'status_persona=1');
    $bc->disconnect();
}
ob_end_flush();
?>