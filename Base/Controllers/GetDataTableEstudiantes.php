<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$model = 'ObservadorEstudianteApp';
$sql = "SELECT OE.id_estudiante, "
        . "IFNULL(CONCAT(P.tipodoc_persona,' ',P.documento_persona),'') as docid_persona, "
        . "OE.nombrecompleto_estudiante, "
        . "P.sexo_persona, "
        . "P.fechanacimiento_persona, "
        . "IFNULL(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(P.fechanacimiento_persona)), '%Y')+0,'?') AS edad_persona, "
        . "IFNULL(M.id_matricula,'') AS id_matricula, "
        . "IFNULL(Pr.nombre_programa,'') AS nombre_programa, "
        . "IFNULL(M.id_planestudio,'') AS id_planestudio, "
        . "IFNULL(M.numgrado_programa,'') AS numgrado_programa, "
        . "IFNULL(M.id_grupo,'') AS id_grupo, "
        . "IFNULL(M.id_periodo,'') AS id_periodo, "
        . "IFNULL(M.fecha_matricula,'') AS fecha_matricula "
        . " FROM "
        . " ObservadorEstudianteApp OE "
        . " LEFT JOIN PersonasApp P ON OE.id_estudiante=P.id_persona "
        . " LEFT JOIN MatriculasApp M ON OE.id_estudiante=M.id_estudiante "
        . " INNER JOIN ProgramasApp Pr ON M.id_programa=Pr.id_programa ";
$where = "";
$bc = null;
$result = null;
if ($session->hasLogin() && $_REQUEST !== null && isset($_REQUEST)) {
    $bc = new BaseController();
    $bc->connect();
    $bc->setAction('findAll');
    $bc->setModel($model);
    $where = " WHERE OE.status_estudiante=1 ";
    if (isset($_POST['findby']) && isset($_POST['findbyvalue']) && strcmp($_POST['findbyvalue'], '') !== 0) {
        $where = $where . " and C." . $_POST['findby'] . " = " . $_POST['findbyvalue'] . " ";
    }
    $where = $where . " ORDER BY M.fecha_matricula DESC, M.id_periodo DESC, OE.nombrecompleto_estudiante ";
    $sql = $sql . $where;
    echo $bc->selectSimple($sql);
    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>