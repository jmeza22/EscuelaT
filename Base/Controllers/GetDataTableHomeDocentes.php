<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$model = 'CargasDocentesApp';
$sql = "SELECT C.*, D.nombrecompleto_docente, A.nombre_asignatura, '" . $variables->getIdCortePeriodo() . "' AS id_corte, '" . $variables->getNumCortePeriodo() . "' AS num_corte "
        . " FROM CargasDocentesApp C INNER JOIN DocentesApp D ON C.id_docente=D.id_docente INNER JOIN AsignaturasApp A ON C.id_asignatura=A.id_asignatura ";
$where = " WHERE C.status_carga=1 and C.id_escuela = " . $session->getEnterpriseID() . " and C.id_docente = '" . $session->getUserID() . "' and C.id_periodo= '" . $variables->getIdPeriodoAnual() . "'";
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null && $variables->getIdCortePeriodo() !== null) {
    $bc = new BasicController();
    $bc->connect();
    $bc->setAction('findAll');
    $bc->setModel($model);
    if (isset($_REQUEST['findby']) && isset($_REQUEST['findbyvalue']) && strcmp($_REQUEST['findbyvalue'], '') !== 0) {
        $where = $where . " and C." . $_REQUEST['findby'] . " = " . $_REQUEST['findbyvalue'] . "";
    }
    $where = $where . " ORDER BY CAST(C.numgrado_programa AS DECIMAL), C.id_grupo, A.nombre_asignatura ";
    $sql = $sql . $where;
    echo $bc->selectJSONArray($sql);
    $bc->disconnect();
    $bc = null;
}
?>