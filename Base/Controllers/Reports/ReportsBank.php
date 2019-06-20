<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BancoReportes
 *
 * @author LISANDRO
 */
class ReportsBank extends BaseController {

    public $session = null;
    private $enterprieseid = null;

    public function __construct($urlsettingsdb = null) {
        parent::__construct($urlsettingsdb);
        $this->session = new SessionManager();
    }

    public function getEnterpriseID() {
        return $this->session->getEnterpriseID();
    }

    public function getEnterpriseName() {
        return $this->session->getEnterpriseName();
    }

    public function getTiposUsuarios() {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM TiposUsuariosApp "
                . "WHERE status_tipousuario=1 "
                . "ORDER BY nombre_tipousuario ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getEscuelas() {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM EscuelasApp "
                . "WHERE status_escuela=1 "
                . "ORDER BY nombre_escuela ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getConfiguracionEscuela($idescuela = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM ConfiguracionApp "
                . "WHERE status_configuracion=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        $sql = $sql . " ORDER BY id_escuela ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getSedes($idescuela = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM SedesApp WHERE status_sede=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        $sql = $sql . " ORDER BY nombre_sede ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getJornadas($idescuela = null, $idsede = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM JornadasApp WHERE status_jornada=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        if ($idsede !== null) {
            $sql = $sql . " AND id_sede='$idsede' ";
        }
        $sql = $sql . " ORDER BY nombre_jornada ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getProgramas($idescuela = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM ProgramasApp WHERE status_programa=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        $sql = $sql . " ORDER BY nombre_programa ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getPlanEstudio($idescuela = null, $idprograma = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM PlanEstudioApp WHERE status_planestudio=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        if ($idprograma !== null) {
            $sql = $sql . " AND id_programa='$idprograma' ";
        }
        $sql = $sql . " ORDER BY id_planestudio ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getPlanEstudioDetalle($idescuela = null, $idprograma = null, $idplanestudio = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT PED.*, A.nombre_asignatura "
                . " FROM PlanEstudioDetalleApp PED "
                . " INNER JOIN AsignaturasApp A ON PED.id_asignatura=A.id_asignatura "
                . " WHERE PED.status_planestudiodetalle=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND PED.id_escuela='$idescuela' ";
        }
        if ($idprograma !== null) {
            $sql = $sql . " AND PED.id_programa='$idprograma' ";
        }
        if ($idplanestudio !== null) {
            $sql = $sql . " AND PED.id_planestudio='$idplanestudio' ";
        }
        $sql = $sql . " ORDER BY PED.id_planestudiodetalle ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getAreas($idescuela = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM AreasApp WHERE status_area=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        $sql = $sql . " ORDER BY nombre_area ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getAsignaturas($idescuela = null, $idarea = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM AsignaturasApp WHERE status_asignatura=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        if ($idarea !== null) {
            $sql = $sql . " AND id_area='$idarea' ";
        }
        $sql = $sql . " ORDER BY nombre_asignatura ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getGrupos($idescuela = null, $idprograma = null, $numgrado = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT G.*, Pr.nombre_programa "
                . " FROM GruposApp G INNER JOIN ProgramasApp Pr "
                . " ON G.id_programa=Pr.id_programa "
                . " WHERE G.status_grupo=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND G.id_escuela='$idescuela' ";
        }
        if ($idprograma !== null) {
            $sql = $sql . " AND G.id_programa='$idprograma' ";
        }
        if ($numgrado !== null) {
            $sql = $sql . " AND G.numgrado_programa='$numgrado' ";
        }
        $sql = $sql . " ORDER BY CAST(G.numgrado_programa AS DECIMAL), G.num_grupo ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getPeriodosAnuales($idescuela = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM PeriodosAnualesApp WHERE status_periodo=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        $sql = $sql . " ORDER BY id_periodo ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getCortesPeriodos($idescuela = null, $idperiodo = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM CortesPeriodosApp WHERE status_corte=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        if ($idperiodo !== null) {
            $sql = $sql . " AND id_periodo='$idperiodo' ";
        }
        $sql = $sql . " ORDER BY id_corte ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getLogrosAsignaturas($idescuela = null, $idasignatura = null, $grado = null, $tipo = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM LogrosAsignaturasApp WHERE status_logro=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        if ($idasignatura !== null) {
            $sql = $sql . " AND id_asignatura='$idasignatura' ";
        }
        if ($grado !== null) {
            $sql = $sql . " AND numgrado_logro='$grado' ";
        }
        if ($tipo !== null) {
            $sql = $sql . " AND tipo_logro='$tipo' ";
        }
        $sql = $sql . " ORDER BY id_logro ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getPersonas() {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM PersonasApp "
                . "WHERE status_persona=1 "
                . "ORDER BY id_persona ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getUsuarios($idescuela = null, $idtipousuario = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM UsuariosApp WHERE status_usuario=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        if ($idtipousuario !== null) {
            $sql = $sql . " AND id_tipousuario='$idtipousuario' ";
        }
        $sql = $sql . " ORDER BY username_usuario, fechacrea_usuario DESC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getDocentes() {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM DocentesApp "
                . "WHERE status_docente=1 "
                . "ORDER BY nombrecompleto_docente ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getDirectoresGrupos($idescuela = null, $idgrupo = null, $idperiodo = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM DirectoresGruposApp WHERE status_director=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        if ($idgrupo !== null) {
            $sql = $sql . " AND id_grupo='$idgrupo' ";
        }
        if ($idperiodo !== null) {
            $sql = $sql . " AND id_periodo='$idperiodo' ";
        }
        $sql = $sql . " ORDER BY id_director DESC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getCargasDocentes($idescuela = null, $idperiodo = null, $idprograma = null, $iddocente = null, $idgrupo = null, $idcarga = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT C.*, D.nombrecompleto_docente, A.nombre_asignatura, P.nombre_programa "
                . " FROM CargasDocentesApp C INNER JOIN DocentesApp D ON C.id_docente=D.id_docente "
                . " INNER JOIN AsignaturasApp A ON C.id_asignatura=A.id_asignatura "
                . " INNER JOIN ProgramasApp P ON C.id_programa=P.id_programa ";
        if ($idescuela !== null) {
            $sql = $sql . " AND C.id_escuela='$idescuela' ";
        }
        if ($idperiodo !== null) {
            $sql = $sql . " AND C.id_periodo='$idperiodo' ";
        }
        if ($idprograma !== null) {
            $sql = $sql . " AND C.id_programa='$idprograma' ";
        }
        if ($iddocente !== null) {
            $sql = $sql . " AND C.id_docente='$iddocente' ";
        }
        if ($idgrupo !== null) {
            $sql = $sql . " AND C.id_grupo='$idgrupo' ";
        }
        if ($idcarga !== null) {
            $sql = $sql . " AND C.id_carga='$idcarga' ";
        }
        $sql = $sql . " ORDER BY D.nombrecompleto_docente, CAST(C.numgrado_programa AS DECIMAL), A.nombre_asignatura ";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getEstudiantes() {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM ObservadorEstudianteApp "
                . "WHERE status_estudiante=1 "
                . "ORDER BY nombrecompleto_estudiante ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getAnotaciones($idestudiante = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM AnotacionesApp WHERE status_anotacion=1 ";
        if ($idestudiante !== null) {
            $sql = $sql . " AND id_estudiante='$idestudiante' ";
        }
        $sql = $sql . " ORDER BY fecha_anotacion DESC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getMatriculas($idescuela = null, $idprograma = null, $numgrado = null, $idgrupo = null, $idestudiante = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM MatriculasApp WHERE status_matricula=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        if ($idprograma !== null) {
            $sql = $sql . " AND id_programa='$idprograma' ";
        }
        if ($numgrado !== null) {
            $sql = $sql . " AND numgrado_programa='$numgrado' ";
        }
        if ($idgrupo !== null) {
            $sql = $sql . " AND id_grupo='$idgrupo' ";
        }
        if ($idestudiante !== null) {
            $sql = $sql . " AND id_estudiante='$idestudiante' ";
        }
        $sql = $sql . " ORDER BY fecha_matricula DESC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getAsignaturasMatriculadas($idescuela = null, $idmatricula = null, $idestudiante = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT MA.*, A.nombre_asignatura "
                . "FROM MatriculaAsignaturasApp MA "
                . "INNER JOIN AsignaturasApp A ON MA.id_asignatura=A.id_asignatura "
                . "WHERE MA.status_matriculaasignatura=1 AND A.status_asignatura=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND MA.id_escuela='$idescuela' ";
        }
        if ($idmatricula !== null) {
            $sql = $sql . " AND MA.id_matricula='$idmatricula' ";
        }
        if ($idestudiante !== null) {
            $sql = $sql . " AND MA.id_estudiante='$idestudiante' ";
        }
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getEstudiantesMatriculas($idescuela = null, $idsede = null, $idprograma = null, $idplanestudio = null, $grado = null, $idgrupo = null, $idperiodo = null, $idmatricula = null, $idestudiante = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT OE.id_estudiante, "
                . "IFNULL(CONCAT(P.tipodoc_persona,' ',P.documento_persona),'') as docid_persona, "
                . "OE.nombrecompleto_estudiante, "
                . "P.sexo_persona, "
                . "P.fechanacimiento_persona, "
                . "IFNULL(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(P.fechanacimiento_persona)), '%Y')+0,'?') AS edad_persona, "
                . "IFNULL(M.id_escuela,'') AS id_escuela, "
                . "IFNULL(M.id_jornada,'') AS id_jornada, "
                . "IFNULL(J.nombre_jornada,'') AS nombre_jornada, "
                . "IFNULL(M.id_sede,'') AS id_sede, "
                . "IFNULL(S.nombre_sede,'') AS nombre_sede, "
                . "IFNULL(M.id_matricula,'') AS id_matricula, "
                . "IFNULL(Pr.id_programa,'') AS id_programa, "
                . "IFNULL(Pr.nombre_programa,'') AS nombre_programa, "
                . "IFNULL(M.id_planestudio,'') AS id_planestudio, "
                . "IFNULL(M.numgrado_programa,'') AS numgrado_programa, "
                . "IFNULL(M.id_grupo,'') AS id_grupo, "
                . "CONCAT(IFNULL(G.numgrado_programa,''),'',IFNULL(G.num_grupo,'')) AS nombre_grupo, "
                . "IFNULL(M.id_periodo,'') AS id_periodo, "
                . "IFNULL(Pe.anualidad_periodo,'') AS anualidad_periodo, "
                . "IFNULL(M.fecha_matricula,'') AS fecha_matricula, "
                . "IFNULL(DG.nombrecompleto_docente,'') AS nombre_director "
                . " FROM "
                . " ObservadorEstudianteApp OE "
                . " LEFT JOIN PersonasApp P ON OE.id_estudiante=P.id_persona "
                . " LEFT JOIN MatriculasApp M ON OE.id_estudiante=M.id_estudiante "
                . " LEFT JOIN PeriodosAnualesApp Pe ON M.id_periodo=Pe.id_periodo "
                . " LEFT JOIN SedesApp S ON M.id_sede=S.id_sede "
                . " LEFT JOIN JornadasApp J ON M.id_jornada=J.id_jornada "
                . " LEFT JOIN GruposApp G ON M.id_grupo=G.id_grupo "
                . " LEFT JOIN DirectoresGruposApp DG ON M.id_grupo=DG.id_grupo "
                . " INNER JOIN ProgramasApp Pr ON M.id_programa=Pr.id_programa "
                . " WHERE OE.status_estudiante=1 AND M.status_matricula=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND M.id_escuela='$idescuela' ";
        }
        if ($idsede !== null) {
            $sql = $sql . " AND M.id_sede='$idsede' ";
        }
        if ($idprograma !== null) {
            $sql = $sql . " AND M.id_programa='$idprograma' ";
        }
        if ($idplanestudio !== null) {
            $sql = $sql . " AND M.id_planestudio='$idplanestudio' ";
        }
        if ($grado !== null) {
            $sql = $sql . " AND M.numgrado_programa='$grado' ";
        }
        if ($idgrupo !== null) {
            $sql = $sql . " AND M.id_grupo='$idgrupo' ";
        }
        if ($idperiodo !== null) {
            $sql = $sql . " AND M.id_periodo='$idperiodo' ";
        }
        if ($idmatricula !== null) {
            $sql = $sql . " AND M.id_matricula='$idmatricula' ";
        }
        if ($idestudiante !== null) {
            $sql = $sql . " AND OE.id_estudiante='$idestudiante' ";
        }
        $sql = $sql . " ORDER BY OE.nombrecompleto_estudiante ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getCalificaciones($idescuela = null, $idsede = null, $idprograma = null, $idplanestudio = null, $grado = null, $idgrupo = null, $idperiodo = null, $idmatricula = null, $idestudiante = null) {
        $sql = null;
        $result = null;
        $porcP1 = null;
        $porcP2 = null;
        $porcP3 = null;
        $porcP4 = null;
        $porcP5 = null;
        $porcP6 = null;
        $resultConfig = null;
        $resultConfig = $this->getConfiguracionEscuela($idescuela);
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

        $sql = "SELECT @rownum := @rownum +1 AS rownum, "
                . " IFNULL(C.id_calificacion,0) as id_calificacion,"
                . " MA.*, "
                . " A.nombre_asignatura, "
                . " AR.nombre_area, "
                . " IFNULL((PED.hpracticas_asignatura+PED.hteoricas_asignatura),'') as horas_asignatura, "
                . " IFNULL(C.p1_nd_calificacion,'') as np1, "
                . " IFNULL(C.p2_nd_calificacion,'') as np2, "
                . " IFNULL(C.p3_nd_calificacion,'') as np3, "
                . " IFNULL(C.p4_nd_calificacion,'') as np4, "
                . " IFNULL(C.p5_nd_calificacion,'') as np5, "
                . " IFNULL(C.p6_nd_calificacion,'') as np6, "
                . " IFNULL(C.phab_nd_calificacion,'') as nphab, "
                . " IFNULL(C.p1_ausencias_calificacion,'') as p1_ausencias_calificacion, "
                . " IFNULL(C.p2_ausencias_calificacion,'') as p2_ausencias_calificacion, "
                . " IFNULL(C.p3_ausencias_calificacion,'') as p3_ausencias_calificacion, "
                . " IFNULL(C.p4_ausencias_calificacion,'') as p4_ausencias_calificacion, "
                . " IFNULL(C.p5_ausencias_calificacion,'') as p5_ausencias_calificacion, "
                . " IFNULL(C.p6_ausencias_calificacion,'') as p6_ausencias_calificacion, "
                . " IFNULL(C.p1_comentarios_calificacion,'') as p1_comentarios_calificacion, "
                . " IFNULL(C.p2_comentarios_calificacion,'') as p2_comentarios_calificacion, "
                . " IFNULL(C.p3_comentarios_calificacion,'') as p3_comentarios_calificacion, "
                . " IFNULL(C.p4_comentarios_calificacion,'') as p4_comentarios_calificacion, "
                . " IFNULL(C.p5_comentarios_calificacion,'') as p5_comentarios_calificacion, "
                . " IFNULL(C.p6_comentarios_calificacion,'') as p6_comentarios_calificacion, "
                . " IFNULL(C.phab_comentarios_calificacion,'') as phab_comentarios_calificacion, "
                . " IFNULL((SELECT lcp1.descripcion_logro FROM LogrosAsignaturasApp lcp1 WHERE lcp1.id_logro=C.p1_logroc_calificacion),'') as p1_descripcion_logroc, "
                . " IFNULL((SELECT lpp1.descripcion_logro FROM LogrosAsignaturasApp lpp1 WHERE lpp1.id_logro=C.p1_logrop_calificacion),'') as p1_descripcion_logrop, "
                . " IFNULL((SELECT lap1.descripcion_logro FROM LogrosAsignaturasApp lap1 WHERE lap1.id_logro=C.p1_logroa_calificacion),'') as p1_descripcion_logroa, "
                . " IFNULL((SELECT lcp2.descripcion_logro FROM LogrosAsignaturasApp lcp2 WHERE lcp2.id_logro=C.p1_logroc_calificacion),'') as p2_descripcion_logroc, "
                . " IFNULL((SELECT lpp2.descripcion_logro FROM LogrosAsignaturasApp lpp2 WHERE lpp2.id_logro=C.p1_logrop_calificacion),'') as p2_descripcion_logrop, "
                . " IFNULL((SELECT lap2.descripcion_logro FROM LogrosAsignaturasApp lap2 WHERE lap2.id_logro=C.p1_logroa_calificacion),'') as p2_descripcion_logroa, "
                . " IFNULL((SELECT lcp3.descripcion_logro FROM LogrosAsignaturasApp lcp3 WHERE lcp3.id_logro=C.p1_logroc_calificacion),'') as p3_descripcion_logroc, "
                . " IFNULL((SELECT lpp3.descripcion_logro FROM LogrosAsignaturasApp lpp3 WHERE lpp3.id_logro=C.p1_logrop_calificacion),'') as p3_descripcion_logrop, "
                . " IFNULL((SELECT lap3.descripcion_logro FROM LogrosAsignaturasApp lap3 WHERE lap3.id_logro=C.p1_logroa_calificacion),'') as p3_descripcion_logroa, "
                . " IFNULL((SELECT lcp4.descripcion_logro FROM LogrosAsignaturasApp lcp4 WHERE lcp4.id_logro=C.p1_logroc_calificacion),'') as p4_descripcion_logroc, "
                . " IFNULL((SELECT lpp4.descripcion_logro FROM LogrosAsignaturasApp lpp4 WHERE lpp4.id_logro=C.p1_logrop_calificacion),'') as p4_descripcion_logrop, "
                . " IFNULL((SELECT lap4.descripcion_logro FROM LogrosAsignaturasApp lap4 WHERE lap4.id_logro=C.p1_logroa_calificacion),'') as p4_descripcion_logroa, "
                . " IFNULL((SELECT lcp5.descripcion_logro FROM LogrosAsignaturasApp lcp5 WHERE lcp5.id_logro=C.p1_logroc_calificacion),'') as p5_descripcion_logroc, "
                . " IFNULL((SELECT lpp5.descripcion_logro FROM LogrosAsignaturasApp lpp5 WHERE lpp5.id_logro=C.p1_logrop_calificacion),'') as p5_descripcion_logrop, "
                . " IFNULL((SELECT lap5.descripcion_logro FROM LogrosAsignaturasApp lap5 WHERE lap5.id_logro=C.p1_logroa_calificacion),'') as p5_descripcion_logroa, "
                . " IFNULL((SELECT lcp6.descripcion_logro FROM LogrosAsignaturasApp lcp6 WHERE lcp6.id_logro=C.p1_logroc_calificacion),'') as p6_descripcion_logroc, "
                . " IFNULL((SELECT lpp6.descripcion_logro FROM LogrosAsignaturasApp lpp6 WHERE lpp6.id_logro=C.p1_logrop_calificacion),'') as p6_descripcion_logrop, "
                . " IFNULL((SELECT lap6.descripcion_logro FROM LogrosAsignaturasApp lap6 WHERE lap6.id_logro=C.p1_logroa_calificacion),'') as p6_descripcion_logroa, "
                . " IFNULL((SELECT lcphab.descripcion_logro FROM LogrosAsignaturasApp lcphab WHERE lcphab.id_logro=C.phab_logroc_calificacion),'') as phab_descripcion_logroc, "
                . " IFNULL((SELECT lpphab.descripcion_logro FROM LogrosAsignaturasApp lpphab WHERE lpphab.id_logro=C.phab_logrop_calificacion),'') as phab_descripcion_logrop, "
                . " IFNULL((SELECT laphab.descripcion_logro FROM LogrosAsignaturasApp laphab WHERE laphab.id_logro=C.phab_logroa_calificacion),'') as phab_descripcion_logroa, "
                . " ROUND(IFNULL((IFNULL(C.p1_nd_calificacion,0)*" . $porcP1 . " + IFNULL(C.p2_nd_calificacion,0)*" . $porcP2 . " + IFNULL(C.p3_nd_calificacion,0)*" . $porcP3 . " + IFNULL(C.p4_nd_calificacion,0)*" . $porcP4 . " + IFNULL(C.p5_nd_calificacion,0)*" . $porcP5 . " + IFNULL(C.p6_nd_calificacion,0)*" . $porcP6 . " ),'0'),1) as def "
                . " FROM (SELECT @rownum :=0) R, "
                . " MatriculaAsignaturasApp MA "
                . " INNER JOIN AsignaturasApp A ON MA.id_asignatura=A.id_asignatura "
                . " INNER JOIN MatriculasApp M ON MA.id_matricula=M.id_matricula "
                . " INNER JOIN ObservadorEstudianteApp OE ON MA.id_estudiante=OE.id_estudiante "
                . " LEFT JOIN PlanEstudioDetalleApp PED ON MA.id_planestudiodetalle=PED.id_planestudiodetalle "
                . " LEFT JOIN CalificacionesApp C ON MA.id_matasig=C.id_matasig "
                . " LEFT JOIN AreasApp AR ON A.id_area=AR.id_area "
                . " WHERE MA.status_matriculaasignatura=1 AND M.status_matricula=1 AND OE.status_estudiante=1 "
        ;
        if ($idescuela !== null) {
            $sql = $sql . " AND M.id_escuela='$idescuela' ";
        }
        if ($idsede !== null) {
            $sql = $sql . " AND M.id_sede='$idsede' ";
        }
        if ($idprograma !== null) {
            $sql = $sql . " AND MA.id_programa='$idprograma' ";
        }
        if ($idplanestudio !== null) {
            $sql = $sql . " AND MA.id_planestudio='$idplanestudio' ";
        }
        if ($grado !== null) {
            $sql = $sql . " AND MA.numgrado_programa='$grado' ";
        }
        if ($idgrupo !== null) {
            $sql = $sql . " AND MA.id_grupo='$idgrupo' ";
        }
        if ($idperiodo !== null) {
            $sql = $sql . " AND MA.id_periodo='$idperiodo' ";
        }
        if ($idmatricula !== null) {
            $sql = $sql . " AND MA.id_matricula='$idmatricula' ";
        }
        if ($idestudiante !== null) {
            $sql = $sql . " AND MA.id_estudiante='$idestudiante' ";
        }
        $sql = $sql . " ORDER BY OE.nombrecompleto_estudiante ";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getInformesCalificaciones($idescuela = null, $idsede = null, $idprograma = null, $idplanestudio = null, $grado = null, $idgrupo = null, $idperiodo = null, $idmatricula = null, $idestudiante = null) {
        $resultEstudiantes = null;
        $resultCalificaciones = null;
        $resultEstudiantes = $this->getEstudiantesMatriculas($idescuela, $idsede, $idprograma, $idplanestudio, $grado, $idgrupo, $idperiodo, $idmatricula, $idestudiante);
        if ($resultEstudiantes !== null && $resultEstudiantes !== '[]') {
            $resultEstudiantes = json_decode($resultEstudiantes, true);
        }
        if ($resultEstudiantes !== null && is_array($resultEstudiantes)) {
            for ($i = 0; $i < count($resultEstudiantes); $i++) {
                $resultCalificaciones = $this->getCalificaciones($resultEstudiantes[$i]['id_escuela'], $resultEstudiantes[$i]['id_sede'], $resultEstudiantes[$i]['id_programa'], $resultEstudiantes[$i]['id_planestudio'], $resultEstudiantes[$i]['numgrado_programa'], $resultEstudiantes[$i]['id_grupo'], $resultEstudiantes[$i]['id_periodo'], $resultEstudiantes[$i]['id_matricula'], $resultEstudiantes[$i]['id_estudiante']);
                if ($resultCalificaciones !== null) {
                    $resultEstudiantes[$i]['calificaciones'] = $resultCalificaciones;
                }
            }
        }
        $resultEstudiantes = json_encode($resultEstudiantes);
        return $resultEstudiantes;
    }

}
