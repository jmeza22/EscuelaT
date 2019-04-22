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
    $colname = " UPPER(concat(apellido1_persona,' ', apellido2_persona,' ', nombre1_persona,' ', nombre2_persona)) ";
    $colvalue = 'id_persona';
    $othervalue = 'documento_persona';
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, ' status_persona=1');
    $bc->disconnect();
}
ob_end_flush();
?>