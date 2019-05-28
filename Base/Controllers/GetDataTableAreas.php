<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$model = 'AreasApp';
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $where = " status_area=1 and id_escuela = " . $session->getEnterpriseID() . " ";
    $where = $where . " ORDER BY id_area ";
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