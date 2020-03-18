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

    if (isset($_POST['findby']) && isset($_POST['findbyvalue']) && strcmp($_POST['findby'], '') !== 0 && strcmp($_POST['findbyvalue'], '') !== 0 && strcmp($_POST['findby'], 'id_carga') === 0) {
        $idcarga = $_POST['findbyvalue'];
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

    $sqlC = "SELECT @rownum := @rownum +1 AS rownum, "
            . " OE.nombrecompleto_estudiante, "
            . " IFNULL(C.id_calificacion,0) AS id_calificacion,"
            . " MA.*, "
            . " IFNULL(C.p1_nd_calificacion,' ') AS np1, "
            . " IFNULL(C.p2_nd_calificacion,' ') AS np2, "
            . " IFNULL(C.p3_nd_calificacion,' ') AS np3, "
            . " IFNULL(C.p4_nd_calificacion,' ') AS np4, "
            . " IFNULL(C.p5_nd_calificacion,' ') AS np5, "
            . " IFNULL(C.p6_nd_calificacion,' ') AS np6, "
            . " IFNULL(C.phab_nd_calificacion,'NO') AS nphab, "
            . " ROUND(IFNULL(("
            . " (IFNULL(C.p1_nd_calificacion,0)*(IFNULL(Cn.p1_porcentaje_configuracion,0)/100)) + "
            . " (IFNULL(C.p2_nd_calificacion,0)*(IFNULL(Cn.p2_porcentaje_configuracion,0)/100)) + "
            . " (IFNULL(C.p3_nd_calificacion,0)*(IFNULL(Cn.p3_porcentaje_configuracion,0)/100)) + "
            . " (IFNULL(C.p4_nd_calificacion,0)*(IFNULL(Cn.p4_porcentaje_configuracion,0)/100)) + "
            . " (IFNULL(C.p5_nd_calificacion,0)*(IFNULL(Cn.p5_porcentaje_configuracion,0)/100)) + "
            . " (IFNULL(C.p6_nd_calificacion,0)*(IFNULL(Cn.p6_porcentaje_configuracion,0)/100))"
            . " ),'0'),1) AS def,";
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
            . " FROM (SELECT @rownum :=0) R, "
            . " MatriculaAsignaturasApp MA "
            . " INNER JOIN ConfiguracionApp Cn ON MA.id_escuela=Cn.id_escuela "
            . " INNER JOIN MatriculasApp M ON MA.id_matricula=M.id_matricula "
            . " LEFT JOIN ObservadorEstudianteApp OE ON MA.id_estudiante=OE.id_estudiante "
            . " LEFT JOIN CalificacionesApp C ON MA.id_matasig=C.id_matasig "
            . " WHERE M.status_matricula=1 "
            . " AND M.estado_matricula!='Retirado' "
            . " AND M.estado_matricula!='' "
            . " AND MA.status_matriculaasignatura=1 "
            . " AND MA.id_escuela = :p_id_escuela "
            . " AND MA.id_programa = :p_id_programa "
            . " AND MA.id_planestudio = :p_id_planestudio "
            . " AND MA.id_asignatura = :p_id_asignatura "
            . " AND MA.numgrado_programa = :p_numgrado_programa "
            . " AND MA.id_grupo = :p_id_grupo "
            . " AND MA.id_periodo = :p_id_periodo "
            . " ORDER BY OE.nombrecompleto_estudiante ASC "
    ;
    $resultMatasig = $bc->selectJSONArray($sqlC, $arraywhere);
    print_r($resultMatasig);

    $bc->disconnect();
    $bc = null;
}
?>