<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$model = 'AnotacionesObservadorApp';
$where = " status_anotacion=1 ";
$bc = null;
if ($session->hasLogin() && $_POST !== null && isset($_POST)) {
    $bc = new BaseController();
    $bc->connect();
    $bc->setAction('findAll');
    $bc->setModel($model);
    if (isset($_POST['findby']) && isset($_POST['findbyvalue']) && strcmp($_POST['findbyvalue'], '') !== 0) {
        $where = " status_anotacion=1 and " . $_POST['findby'] . " = " . $_POST['findbyvalue'] . "";
    }
    echo $bc->selectWithoutModel($model, '*', $where);
    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>