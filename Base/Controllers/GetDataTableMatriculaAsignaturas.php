<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$model = 'MatriculaAsignaturasApp';
$sql = "SELECT MA.*, A.nombre_asignatura "
        . "FROM MatriculaAsignaturasApp MA "
        . "INNER JOIN AsignaturasApp A ON MA.id_asignatura=A.id_asignatura ";
$where = " WHERE MA.id_escuela=" . $session->getEnterpriseID();
$bc = null;
if ($session->hasLogin() && $_POST !== null && isset($_POST)) {
    $bc = new BaseController();
    $bc->connect();
    $bc->setAction('findAll');
    $bc->setModel($model);
    if (isset($_POST['findby']) && isset($_POST['findbyvalue']) && strcmp($_POST['findbyvalue'], '') !== 0) {
        $where = $where . " and MA." . $_POST['findby'] . " = '" . $_POST['findbyvalue'] . "'";
    }
    $sql = $sql . $where;
    echo $bc->selectSimple($sql);
    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>