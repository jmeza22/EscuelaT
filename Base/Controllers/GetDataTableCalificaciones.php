<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$model = 'CalificacionesApp';
$porcP1 = 0;
$porcP2 = 0;
$porcP3 = 0;
$porcP4 = 0;
$porcP5 = 0;
$porcP6 = 0;
$idescuela = null;
$idcarga = null;
$idmatricula = null;
$idperiodo = null;
$idcorte = null;
$numcorte = null;
$iddocente = null;
$idasignatura = null;
$idprograma = null;
$idplanestudio = null;
$numgrado = null;
$idgrupo = null;
$resultCarga = null;
$resultConfig = null;
$resultMatasig = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1)) {
    $bc = new ReportsBank();
    $bc->connect();
    $bc->setAction('findAll');
    $bc->setModel($model);

    $params = $bc->parseFindByToArray($_POST);
    if (isset($params['id_carga']) && $params['id_carga'] !== '') {
        $idcarga = $params['id_carga'];
    }

    $resultCarga = $bc->getCargasDocentes($session->getEnterpriseID(), null, null, null, null, $idcarga);
    $resultCarga = json_decode($resultCarga, true);
    $resultCarga = $resultCarga[0];

    $arraywhere = array();
    $arraywhere['p_id_escuela'] = $resultCarga['id_escuela'];
    $arraywhere['p_id_programa'] = $resultCarga['id_programa'];
    $arraywhere['p_id_planestudio'] = $resultCarga['id_planestudio'];
    $arraywhere['p_id_asignatura'] = $resultCarga['id_asignatura'];
    $arraywhere['p_numgrado_programa'] = $resultCarga['numgrado_programa'];
    $arraywhere['p_id_grupo'] = $resultCarga['id_grupo'];
    $arraywhere['p_id_periodo'] = $variables->getIdPeriodoAnual();
    $iddocente = $resultCarga['id_docente'];
    $idcorte = $variables->getIdCortePeriodo();
    $numcorte = $variables->getNumCortePeriodo();
    $sqlC = "SELECT @rownum := @rownum +1 AS rownum, M.nombrecompleto_estudiante,  "
            . " M.*, MA.id_asignatura, MA.id_matasig, "
            . " IFNULL(C.id_calificacion,0) AS id_calificacion,"
            . " IFNULL(C.p1_nd_calificacion,' ') AS p1_nd_calificacion, "
            . " IFNULL(C.p2_nd_calificacion,' ') AS p2_nd_calificacion, "
            . " IFNULL(C.p3_nd_calificacion,' ') AS p3_nd_calificacion, "
            . " IFNULL(C.p4_nd_calificacion,' ') AS p4_nd_calificacion, "
            . " IFNULL(C.p5_nd_calificacion,' ') AS p5_nd_calificacion, "
            . " IFNULL(C.p6_nd_calificacion,' ') AS p6_nd_calificacion, "
            . " IFNULL(C.phab_nd_calificacion,'NO') AS phab_nd_calificacion, "
            . " ROUND(IFNULL(("
            . " (IFNULL(C.p1_nd_calificacion,0)*(IFNULL(Cn.p1_porcentaje_configuracion,0)/100)) + "
            . " (IFNULL(C.p2_nd_calificacion,0)*(IFNULL(Cn.p2_porcentaje_configuracion,0)/100)) + "
            . " (IFNULL(C.p3_nd_calificacion,0)*(IFNULL(Cn.p3_porcentaje_configuracion,0)/100)) + "
            . " (IFNULL(C.p4_nd_calificacion,0)*(IFNULL(Cn.p4_porcentaje_configuracion,0)/100)) + "
            . " (IFNULL(C.p5_nd_calificacion,0)*(IFNULL(Cn.p5_porcentaje_configuracion,0)/100)) + "
            . " (IFNULL(C.p6_nd_calificacion,0)*(IFNULL(Cn.p6_porcentaje_configuracion,0)/100))"
            . " ),'0'),2) AS def,";
    if ($numcorte !== 'fin') {
        $sqlC = $sqlC . " IFNULL(C.p" . $numcorte . "_logroc_calificacion,'') AS logroc_calificacion, "
                . " IFNULL(C.p" . $numcorte . "_logrop_calificacion,'') AS logrop_calificacion, "
                . " IFNULL(C.p" . $numcorte . "_logroa_calificacion,'') AS logroa_calificacion, "
                . " IFNULL(C.p" . $numcorte . "_nc_calificacion,'') AS nc_calificacion, "
                . " IFNULL(C.p" . $numcorte . "_np_calificacion,'') AS np_calificacion, "
                . " IFNULL(C.p" . $numcorte . "_na_calificacion,'') AS na_calificacion, "
                . " IFNULL(C.p" . $numcorte . "_nn_calificacion,'') AS nn_calificacion, ";
    }
    $sqlC = $sqlC .
            " IFNULL(C.p" . $numcorte . "_nd_calificacion,'') AS nd_calificacion, "
            . " IFNULL(C.p" . $numcorte . "_ausencias_calificacion,'') AS ausencias_calificacion, "
            . " IFNULL(C.p" . $numcorte . "_comentarios_calificacion,'') AS comentarios_calificacion, "
            . " '" . $idcorte . "' AS id_corte "
            . " FROM "
            . " ObservadorEstudianteApp OE "
            . " INNER JOIN MatriculasApp M ON OE.id_estudiante=M.id_estudiante "
            . " INNER JOIN MatriculaAsignaturasApp MA ON MA.id_matricula=M.id_matricula "
            . " INNER JOIN ConfiguracionApp Cn ON M.id_escuela=Cn.id_escuela "
            . " LEFT JOIN CalificacionesApp C ON MA.id_matasig=C.id_matasig, "
            . " (SELECT @rownum :=0) R "
            . " WHERE M.status_matricula=1 "
            . " AND M.estado_matricula!='Retirado' "
            . " AND M.estado_matricula!='' "
            . " AND MA.status_matriculaasignatura=1 "
            . " AND M.id_escuela = :p_id_escuela "
            . " AND M.id_programa = :p_id_programa "
            . " AND M.id_planestudio = :p_id_planestudio "
            . " AND M.numgrado_programa = :p_numgrado_programa "
            . " AND M.id_grupo = :p_id_grupo "
            . " AND M.id_periodo = :p_id_periodo "
            . " AND MA.id_asignatura = :p_id_asignatura "
            . " ORDER BY M.nombrecompleto_estudiante   "
    ;
    $resultMatasig = $bc->selectJSONArray($sqlC, $arraywhere);
    print_r($resultMatasig);

    $bc->disconnect();
    $bc = null;
}
?>