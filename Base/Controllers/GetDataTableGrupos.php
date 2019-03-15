<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$model = 'GruposApp';
$sql = "SELECT G.*, P.nombre_programa FROM GruposApp G INNER JOIN ProgramasApp P ON G.id_programa=P.id_programa ";
$where = " WHERE G.status_grupo=1 and G.id_escuela = ".$session->getEnterpriseID();
$bc = null;
if ($session->hasLogin() && $_POST !== null && isset($_POST)) {
    $bc = new BaseController();
    $bc->connect();
    $bc->setAction('findAll');
    $bc->setModel($model);
    if (isset($_POST['findby']) && isset($_POST['findbyvalue']) && strcmp($_POST['findbyvalue'], '') !== 0) {
        $where = " WHERE G.status_grupo=1 and G.id_escuela = ".$session->getEnterpriseID()." and G." . $_POST['findby'] . " = " . $_POST['findbyvalue'] . "";
        $sql = $sql . $where;
    }
    echo $bc->selectSimple($sql);
    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>