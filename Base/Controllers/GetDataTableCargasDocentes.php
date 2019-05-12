<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$model = 'CargasDocentesApp';
$sql = "SELECT C.*, D.nombrecompleto_docente, A.nombre_asignatura "
        . "FROM CargasDocentesApp C INNER JOIN DocentesApp D ON C.id_docente=D.id_docente INNER JOIN AsignaturasApp A ON C.id_asignatura=A.id_asignatura ";
$bc = null;
if ($session->hasLogin() && $_POST !== null && isset($_POST)) {
    $bc = new BaseController();
    $bc->connect();
    $bc->setAction('findAll');
    $bc->setModel($model);
    $where = " WHERE C.status_carga=1 and C.id_escuela = ".$session->getEnterpriseID();
    if (isset($_POST['findby']) && isset($_POST['findbyvalue']) && strcmp($_POST['findbyvalue'], '') !== 0) {
        $where = $where . " and C." . $_POST['findby'] . " = " . $_POST['findbyvalue'] . " ";
    }
    $where = $where . " ORDER BY D.nombrecompleto_docente, CAST(C.numgrado_programa AS DECIMAL), A.nombre_asignatura";
    $sql = $sql . $where;
    echo $bc->selectSimple($sql);
    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>