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
class BancoReportes extends BaseController {

    private $session = null;
    private $enterprieseid = null;

    public function __construct($urlsettingsdb = null) {
        parent::__construct($urlsettingsdb);
        $this->session = new SessionManager();
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
        $sql = $sql . " ORDER BY nombre_sede ASC";
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

    public function getGrupos($idescuela = null, $idprograma = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT G.*, Pr.nombre_programa "
                . " FROM GruposApp G INNER JOIN ProgramasApp Pr "
                . " ON G.id_programa=Pr.id_programa "
                . " WHERE status_grupo=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND G.id_escuela='$idescuela' ";
        }
        if ($idprograma !== null) {
            $sql = $sql . " AND G.id_programa='$idprograma' ";
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

    public function getUsuarios($idescuela = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM UsuariosApp WHERE status_usuario=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        $sql = $sql . " ORDER BY fechacrea_usuario DESC";
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

    public function getDirectoresGrupos($idescuela = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM DirectoresGruposApp WHERE status_director=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        $sql = $sql . " ORDER BY id_director DESC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getCargasDocentes($idescuela = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT C.*, D.nombrecompleto_docente, A.nombre_asignatura "
                . " FROM CargasDocentesApp C INNER JOIN DocentesApp D ON C.id_docente=D.id_docente "
                . "INNER JOIN AsignaturasApp A ON C.id_asignatura=A.id_asignatura ";
        if ($idescuela !== null) {
            $sql = $sql . " AND C.id_escuela='$idescuela' ";
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

    public function getEstudiantesMatriculas($idescuela = null, $idsede = null, $idprograma = null, $idplanestudio = null, $grado = null, $idgrupo = null, $idmatricula = null, $idestudiante = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT OE.id_estudiante, "
                . "IFNULL(CONCAT(P.tipodoc_persona,' ',P.documento_persona),'') as docid_persona, "
                . "OE.nombrecompleto_estudiante, "
                . "P.sexo_persona, "
                . "P.fechanacimiento_persona, "
                . "IFNULL(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(P.fechanacimiento_persona)), '%Y')+0,'?') AS edad_persona, "
                . "IFNULL(M.id_escuela,'') AS id_escuela, "
                . "IFNULL(M.id_matricula,'') AS id_matricula, "
                . "IFNULL(Pr.id_programa,'') AS id_programa, "
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
        if ($idmatricula !== null) {
            $sql = $sql . " AND M.id_matricula='$idmatricula' ";
        }
        if ($idgrupo !== null) {
            $sql = $sql . " AND OE.id_estudiante='$idestudiante' ";
        }
        $sql = $sql . " ORDER BY OE.nombrecompleto_estudiante ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getInformesCalificaciones($idescuela = null, $idsede = null, $idprograma = null, $idplanestudio = null, $grado = null, $idgrupo = null, $idmatricula = null, $idestudiante = null) {
        $porcP1 = null;
        $porcP2 = null;
        $porcP3 = null;
        $porcP4 = null;
        $porcP5 = null;
        $porcP6 = null;
        $resultConfig = null;
        $resultEstudiantes = null;
        $resultCalificaciones = null;

        $sql0 = "SELECT * FROM ConfiguracionApp "
                . " WHERE id_escuela = " . $idescuela . " ";
        $resultConfig = $this->selectJSONArray($sql0);
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

        $resultEstudiantes = $this->getEstudiantesMatriculas($idescuela, $idsede, $idprograma, $idplanestudio, $grado, $idgrupo, $idmatricula, $idestudiante);
        if ($resultEstudiantes !== null && $resultEstudiantes !== '[]') {
            $resultEstudiantes = json_decode($resultEstudiantes, true);
        }

        if ($resultEstudiantes !== null && is_array($resultEstudiantes)) {
            for ($i = 0; $i < count($resultEstudiantes); $i++) {
                $sql1 = "SELECT @rownum := @rownum +1 AS rownum, "
                        . " IFNULL(C.id_calificacion,0) as id_calificacion,"
                        . " MA.*, "
                        . " A.nombre_asignatura, "
                        . " IFNULL(C.p1_nd_calificacion,'0.0') as np1, "
                        . " IFNULL(C.p2_nd_calificacion,'0.0') as np2, "
                        . " IFNULL(C.p3_nd_calificacion,'0.0') as np3, "
                        . " IFNULL(C.p4_nd_calificacion,'0.0') as np4, "
                        . " IFNULL(C.p5_nd_calificacion,'0.0') as np5, "
                        . " IFNULL(C.p6_nd_calificacion,'0.0') as np6, "
                        . " IFNULL(C.phab_nd_calificacion,'') as nphab, "
                        . " ROUND(IFNULL((IFNULL(C.p1_nd_calificacion,0)*" . $porcP1 . " + IFNULL(C.p2_nd_calificacion,0)*" . $porcP2 . " + IFNULL(C.p3_nd_calificacion,0)*" . $porcP3 . " + IFNULL(C.p4_nd_calificacion,0)*" . $porcP4 . " + IFNULL(C.p5_nd_calificacion,0)*" . $porcP5 . " + IFNULL(C.p6_nd_calificacion,0)*" . $porcP6 . " ),'0'),1) as def "
                        . " FROM (SELECT @rownum :=0) R, "
                        . " MatriculaAsignaturasApp MA "
                        . " INNER JOIN AsignaturasApp A ON MA.id_asignatura=A.id_asignatura "
                        . " INNER JOIN MatriculasApp M ON MA.id_matricula=M.id_matricula "
                        . " INNER JOIN ObservadorEstudianteApp OE ON MA.id_estudiante=OE.id_estudiante "
                        . " LEFT JOIN CalificacionesApp C ON MA.id_matasig=C.id_matasig "
                        . " WHERE MA.id_escuela = '" . $resultEstudiantes[$i]['id_escuela'] . "' "
                        . " and MA.id_programa = '" . $resultEstudiantes[$i]['id_programa'] . "' "
                        . " and MA.id_planestudio = '" . $resultEstudiantes[$i]['id_planestudio'] . "' "
                        . " and MA.id_periodo = '" . $resultEstudiantes[$i]['id_periodo'] . "' "
                        . " and MA.numgrado_programa = '" . $resultEstudiantes[$i]['numgrado_programa'] . "' "
                        . " and MA.id_grupo = '" . $resultEstudiantes[$i]['id_grupo'] . "' "
                        . " and MA.id_matricula = '" . $resultEstudiantes[$i]['id_matricula'] . "' "
                        . " and MA.id_estudiante = '" . $resultEstudiantes[$i]['id_estudiante'] . "' "
                        . " ORDER BY rownum, OE.nombrecompleto_estudiante asc "
                ;
                $resultCalificaciones = $this->selectJSONArray($sql1);

                if ($resultCalificaciones !== null) {
                    $resultEstudiantes[$i]['calificaciones'] = $resultCalificaciones;
                }
            }
        }
        //print_r($resultEstudiantes);
        $resultEstudiantes = json_encode($resultEstudiantes);
        return $resultEstudiantes;
    }

}
