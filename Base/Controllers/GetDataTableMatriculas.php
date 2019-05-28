<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$model = 'MatriculasApp';
$bc = null;
$where = null;
if ($session->hasLogin() && $_POST !== null && isset($_POST)) {
    $bc = new BaseController();
    $bc->connect();
    $bc->setAction('findAll');
    $bc->setModel($model);
    $where = ' status_matricula=1 and id_escuela=' . $session->getEnterpriseID() . ' ';
    if (isset($_POST['findby']) && isset($_POST['findbyvalue']) && strcmp($_POST['findbyvalue'], '') !== 0) {
        $where = $where . " and " . $_POST['findby'] . " = '" . $_POST['findbyvalue'] . "'";
    }
    $where = $where . " ORDER BY fecha_matricula desc ";
    echo $bc->selectWithoutModel($model, '*', $where);
    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>