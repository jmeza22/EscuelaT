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

    $idescuela = $resultCarga['id_escuela'];
    $idprograma = $resultCarga['id_programa'];
    $idplanestudio = $resultCarga['id_planestudio'];
    $idasignatura = $resultCarga['id_asignatura'];
    $numgrado = $resultCarga['numgrado_programa'];
    $idgrupo = $resultCarga['id_grupo'];
    $idperiodo = $variables->getIdPeriodoAnual();
    $idcorte = $variables->getIdCortePeriodo();
    $numcorte = $variables->getNumCortePeriodo();
    $iddocente = $resultCarga['id_docente'];

    $resultConfig = $bc->getConfiguracionEscuela($session->getEnterpriseID());
    $resultConfig = json_decode($resultConfig, true);
    $resultConfig = $resultConfig[0];
    $porcP1 = $resultConfig['p1_porcentaje_configuracion'];
    $porcP2 = $resultConfig['p2_porcentaje_configuracion'];
    $porcP3 = $resultConfig['p3_porcentaje_configuracion'];
    $porcP4 = $resultConfig['p4_porcentaje_configuracion'];
    $porcP5 = $resultConfig['p5_porcentaje_configuracion'];
    $porcP6 = $resultConfig['p6_porcentaje_configuracion'];
    $porcP1 = $porcP1 / 100;
    $porcP2 = $porcP2 / 100;
    $porcP3 = $porcP3 / 100;
    $porcP4 = $porcP4 / 100;
    $porcP5 = $porcP5 / 100;
    $porcP6 = $porcP6 / 100;

    $sql2 = "SELECT @rownum := @rownum +1 AS rownum, "
            . " IFNULL(C.id_calificacion,0) AS id_calificacion,"
            . " MA.*, "
            . " OE.nombrecompleto_estudiante, "
            . " ROUND(IFNULL(C.p1_nd_calificacion+0,''),1) AS np1, "
            . " ROUND(IFNULL(C.p2_nd_calificacion+0,''),1) AS np2, "
            . " ROUND(IFNULL(C.p3_nd_calificacion+0,''),1) AS np3, "
            . " ROUND(IFNULL(C.p4_nd_calificacion+0,''),1) AS np4, "
            . " ROUND(IFNULL(C.p5_nd_calificacion+0,''),1) AS np5, "
            . " ROUND(IFNULL(C.p6_nd_calificacion+0,''),1) AS np6, "
            . " ROUND(IFNULL((IFNULL(C.p1_nd_calificacion,0)*" . $porcP1 . " + IFNULL(C.p2_nd_calificacion,0)*" . $porcP2 . " + IFNULL(C.p3_nd_calificacion,0)*" . $porcP3 . " + IFNULL(C.p4_nd_calificacion,0)*" . $porcP4 . " + IFNULL(C.p5_nd_calificacion,0)*" . $porcP5 . " + IFNULL(C.p6_nd_calificacion,0)*" . $porcP6 . " ),'0'),1) AS def, "
            . " IFNULL(C.p" . $numcorte . "_logroc_calificacion,'') AS logroc_calificacion, "
            . " IFNULL(C.p" . $numcorte . "_logrop_calificacion,'') AS logrop_calificacion, "
            . " IFNULL(C.p" . $numcorte . "_logroa_calificacion,'') AS logroa_calificacion, "
            . " IFNULL(C.p" . $numcorte . "_nc_calificacion,'') AS nc_calificacion, "
            . " IFNULL(C.p" . $numcorte . "_np_calificacion,'') AS np_calificacion, "
            . " IFNULL(C.p" . $numcorte . "_na_calificacion,'') AS na_calificacion, "
            . " IFNULL(C.p" . $numcorte . "_nn_calificacion,'') AS nn_calificacion, "
            . " IFNULL(C.p" . $numcorte . "_nd_calificacion,'') AS nd_calificacion, "
            . " IFNULL(C.p" . $numcorte . "_ausencias_calificacion,'') AS ausencias_calificacion, "
            . " IFNULL(C.p" . $numcorte . "_comentarios_calificacion,'') AS comentarios_calificacion, "
            . " '" . $idcorte . "' AS id_corte "
            . " FROM (SELECT @rownum :=0) R, "
            . " MatriculaAsignaturasApp MA "
            . " INNER JOIN MatriculasApp M ON MA.id_matricula=M.id_matricula "
            . " LEFT JOIN ObservadorEstudianteApp OE ON MA.id_estudiante=OE.id_estudiante "
            . " LEFT JOIN CalificacionesApp C ON MA.id_matasig=C.id_matasig "
            . " WHERE M.status_matricula=1 "
            . " AND MA.status_matriculaasignatura=1 "
            . " AND MA.id_escuela = '" . $idescuela . "' "
            . " AND MA.id_programa = '" . $idprograma . "' "
            . " AND MA.id_planestudio = '" . $idplanestudio . "' "
            . " AND MA.id_asignatura = '" . $idasignatura . "' "
            . " AND MA.numgrado_programa = '" . $numgrado . "' "
            . " AND MA.id_grupo = '" . $idgrupo . "' "
            . " AND MA.id_periodo = '" . $idperiodo . "' "
            . " ORDER BY OE.nombrecompleto_estudiante ASC "
    ;
    $resultMatasig = $bc->selectJSONArray($sql2);
    print_r($resultMatasig);

    $bc->disconnect();
    $bc = null;
}

?>