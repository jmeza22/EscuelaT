<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$model = 'TiposUsuariosApp';
$bc = null;
if (isset($_POST) && $_POST !== null) {
    $where = " status_tipousuario=1 ";
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