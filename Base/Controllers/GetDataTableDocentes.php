<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$model = 'DocentesApp';
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $where = " status_docente=1 ORDER BY nombrecompleto_docente asc ";
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