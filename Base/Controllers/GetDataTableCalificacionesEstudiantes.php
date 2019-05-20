<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$model = 'CalificacionesApp';
$porcP1 = null;
$porcP2 = null;
$porcP3 = null;
$porcP4 = null;
$porcP5 = null;
$porcP6 = null;
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
$resultMat = null;
$resultConfig = null;
$resultMatasig = null;
if ($session->hasLogin() && $_REQUEST !== null && isset($_REQUEST)) {
    $bc = new BaseController();
    $bc->connect();
    $bc->setAction('findAll');
    $bc->setModel($model);

    if (isset($_REQUEST['findby']) && isset($_REQUEST['findbyvalue']) && strcmp($_REQUEST['findby'], '') !== 0 && strcmp($_REQUEST['findbyvalue'], '') !== 0 && strcmp($_REQUEST['findby'], 'id_matricula') === 0) {
        $idmatricula = $_REQUEST['findbyvalue'];
    }

    $sql0 = "SELECT * FROM MatriculasApp "
            . "WHERE id_matricula = '" . $idmatricula . "' ";
    $resultMat = $bc->selectSimple($sql0);
    $resultMat = json_decode($resultMat, true);
    $resultMat = $resultMat[0];

    $idescuela = $resultMat['id_escuela'];
    $idprograma = $resultMat['id_programa'];
    $idplanestudio = $resultMat['id_planestudio'];
    $numgrado = $resultMat['numgrado_programa'];
    $idgrupo = $resultMat['id_grupo'];
    $idperiodo = $resultMat['id_periodo'];
    
    $sql1 = "SELECT * FROM ConfiguracionApp "
            . "WHERE id_escuela = " . $idescuela . " ";
    $resultConfig = $bc->selectSimple($sql1);
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
            . " IFNULL(C.id_calificacion,0) as id_calificacion,"
            . " MA.*, "
            . " A.nombre_asignatura, "
            . " IFNULL(C.p1_nd_calificacion,'') as np1, "
            . " IFNULL(C.p2_nd_calificacion,'') as np2, "
            . " IFNULL(C.p3_nd_calificacion,'') as np3, "
            . " IFNULL(C.p4_nd_calificacion,'') as np4, "
            . " IFNULL(C.p5_nd_calificacion,'') as np5, "
            . " IFNULL(C.p6_nd_calificacion,'') as np6, "
            . " ROUND(IFNULL((IFNULL(C.p1_nd_calificacion,0)*".$porcP1." + IFNULL(C.p2_nd_calificacion,0)*".$porcP2." + IFNULL(C.p3_nd_calificacion,0)*".$porcP3." + IFNULL(C.p4_nd_calificacion,0)*".$porcP4." + IFNULL(C.p5_nd_calificacion,0)*".$porcP5." + IFNULL(C.p6_nd_calificacion,0)*".$porcP6." ),'0'),1) as def "
            . " FROM (SELECT @rownum :=0) R, "
            . " MatriculaAsignaturasApp MA "
            . " INNER JOIN AsignaturasApp A ON MA.id_asignatura=A.id_asignatura "
            . " INNER JOIN MatriculasApp M ON MA.id_matricula=M.id_matricula "
            . " INNER JOIN ObservadorEstudianteApp OE ON MA.id_estudiante=OE.id_estudiante "
            . " LEFT JOIN CalificacionesApp C ON MA.id_matasig=C.id_matasig "
            . " WHERE MA.id_escuela = '" . $idescuela . "' "
            . " and MA.id_matricula = '" . $idmatricula . "' "
            . " and MA.id_periodo = '" . $idperiodo . "' "
            . " ORDER BY rownum, OE.nombrecompleto_estudiante asc "
    ;
    //echo $sql2;
    $resultMatasig = $bc->selectSimple($sql2);
    //$resultMatasig = json_decode($resultMatasig, true);
    print_r($resultMatasig);

    $bc->disconnect();
    $bc = null;
}
ob_end_flush();
?>