<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$model = 'SedesApp';
$bc = null;
if ($_POST !== null && isset($_POST)) {
    
    $where = " status_sede=1 and id_escuela='".$session->getEnterpriseID()."'";
    $bc = new BaseController();
    $bc->connect();
    $bc->setAction('findAll');
    $bc->setModel($model);
    echo $bc->selectWithoutModel($model, '*', $where);
    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>