<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();

    $arraywhere = $bc->parseFindByToArray($_POST);
    if (!isset($arraywhere['id_escuela'])) {
        $arraywhere['id_escuela'] = $session->getEnterpriseID();
    }
    if (!isset($arraywhere['id_programa'])) {
        $arraywhere['id_programa'] = null;
    }
    if (!isset($arraywhere['id_asignatura'])) {
        $arraywhere['id_asignatura'] = null;
    }
    if (!isset($arraywhere['id_periodo'])) {
        $arraywhere['id_periodo'] = null;
    }
    if (!isset($arraywhere['numgrado_programa'])) {
        $arraywhere['numgrado_programa'] = null;
    }
    if (!isset($arraywhere['id_grupo'])) {
        $arraywhere['id_grupo'] = null;
    }
    if (!isset($arraywhere['fecha_asistencia'])) {
        $arraywhere['fecha_asistencia'] = null;
    }
    $result = $bc->getAsistencias($session->getEnterpriseID(), null, null, $arraywhere['id_programa'], $arraywhere['id_asignatura'], $arraywhere['id_periodo'], $arraywhere['numgrado_programa'], $arraywhere['id_grupo'], $arraywhere['fecha_asistencia']);
    if ($result === null || $result === '' || $result === '[]') {
        $arraywhere['id_corte'] = $variables->getIdCortePeriodo();
        $sql = "SELECT M.*, MA.id_matasig, MA.id_asignatura, A.nombre_asignatura,  "
                . "'0' AS id_asistencia, '1' AS presente_asistencia, '0' AS tarde_asistencia, :fecha_asistencia AS fecha_asistencia, '' AS nota_asistencia, :id_corte AS id_corte, @rownum := @rownum +1 AS rownum "
                . "FROM (SELECT @rownum :=0) R, MatriculaAsignaturasApp MA "
                . "INNER JOIN MatriculasApp M ON MA.id_matricula=M.id_matricula "
                . "INNER JOIN AsignaturasApp A ON MA.id_asignatura=A.id_asignatura "
                . "WHERE M.status_matricula=1 AND MA.status_matriculaasignatura=1 AND A.status_asignatura=1 ";
        if (isset($arraywhere['id_escuela'])) {
            $sql = $sql . " AND M.id_escuela=:id_escuela ";
        }
        if (isset($arraywhere['id_programa'])) {
            $sql = $sql . " AND M.id_programa=:id_programa ";
        }
        if (isset($arraywhere['id_asignatura'])) {
            $sql = $sql . " AND MA.id_asignatura=:id_asignatura ";
        }
        if (isset($arraywhere['id_periodo'])) {
            $sql = $sql . " AND M.id_periodo=:id_periodo ";
        }
        if (isset($arraywhere['numgrado_programa'])) {
            $sql = $sql . " AND M.numgrado_programa=:numgrado_programa ";
        }
        if (isset($arraywhere['id_grupo'])) {
            $sql = $sql . " AND M.id_grupo=:id_grupo ";
        }
        $sql = $sql . " ORDER BY M.nombrecompleto_estudiante";
        $result = $bc->selectJSONArray($sql, $arraywhere);
    }
    echo $result;
    $bc->disconnect();
    $bc = null;
}
?>