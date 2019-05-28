<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$model = 'PlanEstudioDetalleApp';
$sql = "SELECT PED.*, A.nombre_asignatura "
        . " FROM PlanEstudioDetalleApp PED "
        . " INNER JOIN AsignaturasApp A ON PED.id_asignatura=A.id_asignatura ";
$where = " WHERE PED.status_planestudiodetalle=1 ";
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new BaseController();
    $bc->connect();
    $bc->setAction('findAll');
    $bc->setModel($model);
    if (isset($_POST['findby']) && isset($_POST['findbyvalue']) && strcmp($_POST['findbyvalue'], '') !== 0) {
        $where = $where . " and PED." . $_POST['findby'] . " = " . $_POST['findbyvalue'] . "";
    }
    $where = $where . " ORDER BY PED.id_planestudio, CAST(PED.numgrado_programa AS DECIMAL), PED.id_asignatura ";
    $sql = $sql . $where;
    echo $bc->selectSimple($sql);
    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>