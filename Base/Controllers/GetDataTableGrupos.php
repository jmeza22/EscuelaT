<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$model = 'GruposApp';
$sql = "SELECT G.*, P.nombre_programa "
        . "FROM GruposApp G INNER JOIN ProgramasApp P ON G.id_programa=P.id_programa ";
$where = " WHERE G.status_grupo=1 and G.id_escuela = " . $session->getEnterpriseID();
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new BaseController();
    $bc->connect();
    $bc->setAction('findAll');
    $bc->setModel($model);
    if (isset($_POST['findby']) && isset($_POST['findbyvalue']) && strcmp($_POST['findbyvalue'], '') !== 0) {
        $where = $where . " and G." . $_POST['findby'] . " = " . $_POST['findbyvalue'] . " ";
    }
    $where = $where . " ORDER BY CAST(G.numgrado_programa AS DECIMAL), G.num_grupo";
    $sql = $sql . $where;
    echo $bc->selectSimple($sql);
    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>