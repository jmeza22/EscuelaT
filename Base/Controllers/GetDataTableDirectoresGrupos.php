<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$model = 'DirectoresGruposApp';
$bc = null;
if ($session->hasLogin() && $_POST !== null && isset($_POST)) {
    $where = " status_director=1 and id_escuela=".$session->getEnterpriseID()." ORDER BY id_director desc ";
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