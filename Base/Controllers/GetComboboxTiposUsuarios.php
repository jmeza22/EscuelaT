<?php


include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
if ($_POST != null && isset($_POST)) {
    $bc = new ReportsBank();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('TiposUsuariosApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colname = "nombre_tipousuario";
    $colvalue = "id_tipousuario";
    $othervalue = "id_tipousuario";
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, 'status_tipousuario=1');
    $bc->disconnect();
}

?>