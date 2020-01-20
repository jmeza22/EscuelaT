<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * AND open the template in the editor.
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
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        $sql = $sql . " ORDER BY nombre_sede ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getJornadas($idescuela = null, $idsede = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM JornadasApp WHERE status_jornada=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($idsede !== null) {
            $arraywhere['p_id_sede'] = $idsede;
            $sql = $sql . " AND id_sede=:p_id_sede ";
        }
        $sql = $sql . " ORDER BY nombre_jornada ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getProgramas($idescuela = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM ProgramasApp WHERE status_programa=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        $sql = $sql . " ORDER BY nombre_programa ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getPlanEstudio($idescuela = null, $idprograma = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM PlanEstudioApp WHERE status_planestudio=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_prograna'] = $idprograma;
            $sql = $sql . " AND id_programa=:p_id_programa ";
        }
        $sql = $sql . " ORDER BY id_planestudio ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getPlanEstudioDetalle($idescuela = null, $idprograma = null, $idplanestudio = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT PED.*, A.nombre_asignatura "
                . " FROM PlanEstudioDetalleApp PED "
                . " INNER JOIN AsignaturasApp A ON PED.id_asignatura=A.id_asignatura "
                . " WHERE PED.status_planestudiodetalle=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND PED.id_escuela=:p_id_escuela ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_prograna'] = $idprograma;
            $sql = $sql . " AND PED.id_programa=:p_id_programa ";
        }
        if ($idplanestudio !== null) {
            $arraywhere['p_id_planestudio'] = $idplanestudio;
            $sql = $sql . " AND PED.id_planestudio=:p_id_planestudio ";
        }
        $sql = $sql . " ORDER BY CAST(PED.numgrado_programa as DECIMAL), A.nombre_asignatura, PED.id_planestudiodetalle ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getPlanEstudioDetallado($idescuela = null, $idprograma = null) {
        $plan = $this->getPlanEstudio($idescuela, $idprograma);
        if ($plan !== null && $plan !== '' && $plan !== '[]') {
            $plan = json_decode($plan, true);
            if ($plan !== null && is_array($plan)) {
                for ($i = 0; $i < count($plan); $i++) {
                    $plan[$i]['detalle'] = $this->getPlanEstudioDetalle($plan['id_escuela'], $plan['id_programa'], $plan['id_planestudio']);
                }
            }
        }
        return $plan;
    }

    public function getAreas($idescuela = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM AreasApp WHERE status_area=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        $sql = $sql . " ORDER BY nombre_area ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getAsignaturas($idescuela = null, $idarea = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM AsignaturasApp WHERE status_asignatura=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($idarea !== null) {
            $arraywhere['p_id_area'] = $idarea;
            $sql = $sql . " AND id_area=:p_id_area ";
        }
        $sql = $sql . " ORDER BY nombre_asignatura ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getGrados($idescuela = null, $idprograma = null, $idplanestudio = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT PED.numgrado_programa, Pr.nombre_programa, PES.descripcion_planestudio "
                . " FROM "
                . " PlanEstudioDetalleApp PED INNER JOIN PlanEstudiosApp PES "
                . " ON PED.id_planestudio=PES.id_planestudio "
                . " INNER JOIN ProgramasApp Pr "
                . " ON PED.id_programa=Pr.id_programa "
                . " WHERE PED.status_planestudiodetalle=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND PED.id_escuela=:p_id_escuela ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND PED.id_programa=:p_id_programa ";
        }
        if ($idplanestudio !== null) {
            $arraywhere['p_id_planestudio'] = $idplanestudio;
            $sql = $sql . " AND PED.id_planestudio=:p_id_planestudio ";
        }
        $sql = $sql . " GROUP BY PED.numgrado_programa ORDER BY CAST(PED.numgrado_programa AS DECIMAL)";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getGrupos($idescuela = null, $idprograma = null, $numgrado = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT G.*, Pr.nombre_programa "
                . " FROM GruposApp G INNER JOIN ProgramasApp Pr "
                . " ON G.id_programa=Pr.id_programa "
                . " WHERE G.status_grupo=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND G.id_escuela=:p_id_escuela ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND G.id_programa=:p_id_programa ";
        }
        if ($numgrado !== null) {
            $arraywhere['p_numgrado_programa'] = $numgrado;
            $sql = $sql . " AND G.numgrado_programa=:p_numgrado_programa ";
        }
        $sql = $sql . " ORDER BY CAST(G.numgrado_programa AS DECIMAL), G.num_grupo ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getPeriodosAnuales($idescuela = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM PeriodosAnualesApp WHERE status_periodo=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        $sql = $sql . " ORDER BY id_periodo DESC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getCortesPeriodos($idescuela = null, $idperiodo = null, $idcorte = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM CortesPeriodosApp WHERE status_corte=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($idperiodo !== null) {
            $arraywhere['p_id_periodo'] = $idperiodo;
            $sql = $sql . " AND id_periodo=:p_id_periodo ";
        }
        if ($idcorte !== null) {
            $arraywhere['p_id_corte'] = $idcorte;
            $sql = $sql . " AND id_corte=:p_id_corte ";
        }
        $sql = $sql . " ORDER BY id_corte ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getLogrosAsignaturas($idescuela = null, $idasignatura = null, $grado = null, $tipo = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT L.*, A.nombre_asignatura "
                . " FROM LogrosAsignaturasApp L INNER JOIN AsignaturasApp A ON L.id_asignatura=A.id_asignatura "
                . " WHERE L.status_logro=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND L.id_escuela=:p_id_escuela ";
        }
        if ($idasignatura !== null) {
            $arraywhere['p_id_asignatura'] = $idasignatura;
            $sql = $sql . " AND L.id_asignatura=:p_id_asignatura ";
        }
        if ($grado !== null) {
            $arraywhere['p_num_grado'] = $grado;
            $sql = $sql . " AND L.numgrado_logro=:p_num_grado ";
        }
        if ($tipo !== null) {
            $arraywhere['p_tipo_logro'] = $tipo;
            $sql = $sql . " AND L.tipo_logro=:p_tipo_logro ";
        }
        $sql = $sql . " ORDER BY L.num_logro DESC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getPersonas() {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM PersonasApp "
                . "WHERE status_persona=1 "
                . "ORDER BY num_persona DESC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getUsuarios($idescuela = null, $idtipousuario = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM UsuariosApp WHERE status_usuario=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($idtipousuario !== null) {
            $arraywhere['p_id_tipousuario'] = $idtipousuario;
            $sql = $sql . " AND id_tipousuario=:p_id_tipousuario ";
        }
        $sql = $sql . " ORDER BY fechacrea_usuario DESC, username_usuario";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getDocentes($iddocente = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM DocentesApp "
                . "WHERE status_docente=1 ";
        $arraywhere = Array();
        if ($iddocente !== null) {
            $arraywhere['p_id_docente'] = $iddocente;
            $sql = $sql . " AND id_docente=:p_id_docente ";
        }
        $sql = $sql . "ORDER BY nombrecompleto_docente ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getDirectoresGrupos($idescuela = null, $idgrupo = null, $idperiodo = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM DirectoresGruposApp WHERE status_director=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($idgrupo !== null) {
            $arraywhere['p_id_grupo'] = $idgrupo;
            $sql = $sql . " AND id_grupo=:p_id_grupo ";
        }
        if ($idperiodo !== null) {
            $arraywhere['p_id_periodo'] = $idperiodo;
            $sql = $sql . " AND id_periodo=:p_id_periodo ";
        }
        $sql = $sql . " ORDER BY id_director DESC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getCargasDocentes($idescuela = null, $idperiodo = null, $idprograma = null, $iddocente = null, $idgrupo = null, $idcarga = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT C.*, D.nombrecompleto_docente, A.nombre_asignatura, P.nombre_programa "
                . " FROM CargasDocentesApp C INNER JOIN DocentesApp D ON C.id_docente=D.id_docente "
                . " INNER JOIN AsignaturasApp A ON C.id_asignatura=A.id_asignatura "
                . " INNER JOIN ProgramasApp P ON C.id_programa=P.id_programa ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND C.id_escuela=:p_id_escuela ";
        }
        if ($idperiodo !== null) {
            $arraywhere['p_id_periodo'] = $idperiodo;
            $sql = $sql . " AND C.id_periodo=:p_id_periodo ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND C.id_programa=:p_id_programa ";
        }
        if ($iddocente !== null) {
            $arraywhere['p_id_docente'] = $iddocente;
            $sql = $sql . " AND C.id_docente=:p_id_docente ";
        }
        if ($idgrupo !== null) {
            $arraywhere['p_id_grupo'] = $idgrupo;
            $sql = $sql . " AND C.id_grupo=:p_id_grupo ";
        }
        if ($idcarga !== null) {
            $arraywhere['p_id_carga'] = $idcarga;
            $sql = $sql . " AND C.id_carga=:p_id_carga ";
        }
        $sql = $sql . " ORDER BY D.nombrecompleto_docente, CAST(C.numgrado_programa AS DECIMAL), A.nombre_asignatura ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getEstudiantes($idestudiante = null) {
        $sql = null;
        $result = null;
        $arraywhere = Array();
        $sql = "SELECT OE.*, P.*, IFNULL(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(P.fechanacimiento_persona)), '%Y')+0,'?') AS edad_persona  "
                . " FROM ObservadorEstudianteApp OE "
                . " INNER JOIN PersonasApp P ON OE.id_estudiante=P.id_persona "
                . " WHERE OE.status_estudiante=1 ";
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND OE.id_estudiante=:p_id_estudiante ";
        }
        $sql = $sql . " ORDER BY OE.nombrecompleto_estudiante ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getAcudientes($idestudiante = null) {
        $sql = null;
        $result = null;
        $arraywhere = Array();
        $sql = "SELECT A1.nombreacudiente1_estudiante, A1.idacudiente1_estudiante FROM ObservadorEstudianteApp A1 "
                . "WHERE A1.status_estudiante=1 ";
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND A1.id_estudiante=:p_id_estudiante ";
        }
        $sql = $sql . " UNION ";
        $sql = $sql . "SELECT A2.nombreacudiente2_estudiante, A2.idacudiente2_estudiante FROM ObservadorEstudianteApp A2 "
                . "WHERE A2.status_estudiante=1 ";
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND A2.id_estudiante=:p_id_estudiante ";
        }
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getAnotaciones($idestudiante = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM AnotacionesObservadorApp WHERE status_anotacion=1 ";
        $arraywhere = Array();
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND id_estudiante=:p_id_estudiante ";
        }
        $sql = $sql . " ORDER BY fecha_anotacion DESC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getCitaciones($idestudiante = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM CitacionesObservadorApp WHERE status_citacion=1 ";
        $arraywhere = Array();
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND id_estudiante=:p_id_estudiante ";
        }
        $sql = $sql . " ORDER BY fecha_citacion DESC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getMatriculas($idescuela = null, $idsede = null, $idjornada = null, $idprograma = null, $idplanestudio = null, $numgrado = null, $idgrupo = null, $idperiodo = null, $idestudiante = null, $idmatricula = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM MatriculasApp WHERE status_matricula=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($idsede !== null) {
            $arraywhere['p_id_sede'] = $idsede;
            $sql = $sql . " AND id_sede=:p_id_sede ";
        }
        if ($idjornada !== null) {
            $arraywhere['p_id_jornada'] = $idjornada;
            $sql = $sql . " AND id_jornada=:p_id_jornada ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND id_programa=:p_id_programa ";
        }
        if ($idplanestudio !== null) {
            $arraywhere['p_id_planestudio'] = $idplanestudio;
            $sql = $sql . " AND id_planestudio=:p_id_planestudio ";
        }
        if ($numgrado !== null) {
            $arraywhere['p_num_grado'] = $numgrado;
            $sql = $sql . " AND numgrado_programa=:p_num_grado ";
        }
        if ($idgrupo !== null) {
            $arraywhere['p_id_grupo'] = $idgrupo;
            $sql = $sql . " AND id_grupo=:p_id_grupo ";
        }
        if ($idperiodo !== null) {
            $arraywhere['p_id_periodo'] = $idperiodo;
            $sql = $sql . " AND id_periodo=:p_id_periodo ";
        }
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND id_estudiante=:p_id_estudiante ";
        }
        if ($idmatricula !== null) {
            $arraywhere['p_id_matricula'] = $idmatricula;
            $sql = $sql . " AND id_matricula=:p_id_matricula ";
        }
        $sql = $sql . " ORDER BY CAST(numgrado_programa AS DECIMAL), fecha_matricula DESC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getAsignaturasMatriculadas($idescuela = null, $idmatricula = null, $idestudiante = null, $idprograma = null, $idasignatura = null, $idperiodo = null, $grado = null, $idgrupo = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT MA.*, A.nombre_asignatura, OE.nombrecompleto_estudiante "
                . "FROM MatriculaAsignaturasApp MA "
                . "INNER JOIN AsignaturasApp A ON MA.id_asignatura=A.id_asignatura "
                . "INNER JOIN ObservadorEstudianteApp OE ON MA.id_estudiante=OE.id_estudiante "
                . "WHERE MA.status_matriculaasignatura=1 AND A.status_asignatura=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND MA.id_escuela=:p_id_escuela ";
        }
        if ($idmatricula !== null) {
            $arraywhere['p_id_matricula'] = $idmatricula;
            $sql = $sql . " AND MA.id_matricula=:p_id_matricula ";
        }
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND MA.id_estudiante=:p_id_estudiante ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND MA.id_programa=:p_id_programa ";
        }
        if ($idasignatura !== null) {
            $arraywhere['p_id_asignatura'] = $idasignatura;
            $sql = $sql . " AND MA.id_asignatura=:p_id_asignatura ";
        }
        if ($idperiodo !== null) {
            $arraywhere['p_id_periodo'] = $idperiodo;
            $sql = $sql . " AND MA.id_periodo=:p_id_periodo ";
        }
        if ($grado !== null) {
            $arraywhere['p_num_grado'] = $grado;
            $sql = $sql . " AND MA.numgrado_programa=:p_num_grado ";
        }
        if ($idgrupo !== null) {
            $arraywhere['p_id_grupo'] = $idgrupo;
            $sql = $sql . " AND MA.id_grupo=:p_id_grupo ";
        }
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getAsistencias($idescuela = null, $idmatricula = null, $idestudiante = null, $idprograma = null, $idasignatura = null, $idperiodo = null, $grado = null, $idgrupo = null, $fecha = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT @rownum := @rownum +1 AS rownum, MA.*, A.nombre_asignatura, OE.nombrecompleto_estudiante, "
                . "IFNULL(Asi.id_asistencia, '0') AS id_asistencia, IFNULL(Asi.presente_asistencia,1) AS presente_asistencia, IFNULL(Asi.tarde_asistencia,0) AS tarde_asistencia, IFNULL(Asi.fecha_asistencia,:p_fecha_asistencia) AS fecha_asistencia, IFNULL(Asi.nota_asistencia,'') AS nota_asistencia, Asi.id_corte "
                . "FROM (SELECT @rownum :=0) R, MatriculaAsignaturasApp MA "
                . "INNER JOIN AsignaturasApp A ON MA.id_asignatura=A.id_asignatura "
                . "INNER JOIN ObservadorEstudianteApp OE ON MA.id_estudiante=OE.id_estudiante "
                . "LEFT JOIN AsistenciaApp Asi ON MA.id_matasig=Asi.id_matasig "
                . "WHERE MA.status_matriculaasignatura=1 AND A.status_asignatura=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND MA.id_escuela=:p_id_escuela ";
        }
        if ($idmatricula !== null) {
            $arraywhere['p_id_matricula'] = $idmatricula;
            $sql = $sql . " AND MA.id_matricula=:p_id_matricula ";
        }
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND MA.id_estudiante=:p_id_estudiante ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND MA.id_programa=:p_id_programa ";
        }
        if ($idasignatura !== null) {
            $arraywhere['p_id_asignatura'] = $idasignatura;
            $sql = $sql . " AND MA.id_asignatura=:p_id_asignatura ";
        }
        if ($idperiodo !== null) {
            $arraywhere['p_id_periodo'] = $idperiodo;
            $sql = $sql . " AND MA.id_periodo=:p_id_periodo ";
        }
        if ($grado !== null) {
            $arraywhere['p_num_grado'] = $grado;
            $sql = $sql . " AND MA.numgrado_programa=:p_num_grado ";
        }
        if ($idgrupo !== null) {
            $arraywhere['p_id_grupo'] = $idgrupo;
            $sql = $sql . " AND MA.id_grupo=:p_id_grupo ";
        }
        if ($fecha !== null) {
            $arraywhere['p_fecha_asistencia'] = $fecha;
            $sql = $sql . " AND Asi.fecha_asistencia=:p_fecha_asistencia ";
        }
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getEstudiantesMatriculas($idescuela = null, $idsede = null, $idjornada = null, $idprograma = null, $idplanestudio = null, $grado = null, $idgrupo = null, $idperiodo = null, $idestudiante = null, $idmatricula = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT OE.id_estudiante, "
                . "P.* , "
                . "OE.* , "
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
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND M.id_escuela=:p_id_escuela ";
        }
        if ($idsede !== null) {
            $arraywhere['p_id_sede'] = $idsede;
            $sql = $sql . " AND M.id_sede=:p_id_sede ";
        }
        if ($idjornada !== null) {
            $arraywhere['p_id_jornada'] = $idjornada;
            $sql = $sql . " AND M.id_jornada=:p_id_jornada ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND M.id_programa=:p_id_programa ";
        }
        if ($idplanestudio !== null) {
            $arraywhere['p_id_planestudio'] = $idplanestudio;
            $sql = $sql . " AND M.id_planestudio=:p_id_planestudio ";
        }
        if ($grado !== null) {
            $arraywhere['p_num_grado'] = $grado;
            $sql = $sql . " AND M.numgrado_programa=:p_num_grado ";
        }
        if ($idgrupo !== null) {
            $arraywhere['p_id_grupo'] = $idgrupo;
            $sql = $sql . " AND M.id_grupo=:p_id_grupo ";
        }
        if ($idperiodo !== null) {
            $arraywhere['p_id_periodo'] = $idperiodo;
            $sql = $sql . " AND M.id_periodo=:p_id_periodo ";
        }
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND M.id_estudiante=:p_id_estudiante ";
        }
        if ($idmatricula !== null) {
            $arraywhere['p_id_matricula'] = $idmatricula;
            $sql = $sql . " AND M.id_matricula=:p_id_matricula ";
        }
        $sql = $sql . " ORDER BY CAST(M.numgrado_programa AS DECIMAL), OE.nombrecompleto_estudiante ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getCalificaciones($idescuela = null, $idsede = null, $idjornada = null, $idprograma = null, $idplanestudio = null, $grado = null, $idgrupo = null, $idperiodo = null, $idestudiante = null, $idmatricula = null) {
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
                . " C.id_calificacion AS id_calificacion,"
                . " MA.*, "
                . " A.nombre_asignatura, "
                . " M.nombrecompleto_estudiante, "
                . " AR.nombre_area, "
                . " PED.hteoricas_asignatura AS hteoricas_asignatura, "
                . " PED.hpracticas_asignatura AS hpracticas_asignatura, "
                . " ROUND(IFNULL(C.p1_nd_calificacion+0,''),1) AS np1, "
                . " ROUND(IFNULL(C.p2_nd_calificacion+0,''),1) AS np2, "
                . " ROUND(IFNULL(C.p3_nd_calificacion+0,''),1) AS np3, "
                . " ROUND(IFNULL(C.p4_nd_calificacion+0,''),1) AS np4, "
                . " ROUND(IFNULL(C.p5_nd_calificacion+0,''),1) AS np5, "
                . " ROUND(IFNULL(C.p6_nd_calificacion+0,''),1) AS np6, "
                . " ROUND(IFNULL(C.phab_nd_calificacion,''),1) AS nphab, "
                . " ROUND(IFNULL(C.pfin_nd_calificacion,''),1) AS npfin, "
                . " IFNULL(C.p1_ausencias_calificacion,'') AS p1_ausencias_calificacion, "
                . " IFNULL(C.p2_ausencias_calificacion,'') AS p2_ausencias_calificacion, "
                . " IFNULL(C.p3_ausencias_calificacion,'') AS p3_ausencias_calificacion, "
                . " IFNULL(C.p4_ausencias_calificacion,'') AS p4_ausencias_calificacion, "
                . " IFNULL(C.p5_ausencias_calificacion,'') AS p5_ausencias_calificacion, "
                . " IFNULL(C.p6_ausencias_calificacion,'') AS p6_ausencias_calificacion, "
                . " IFNULL(C.pfin_ausencias_calificacion,'') AS pfin_ausencias_calificacion, "
                . " IFNULL(C.p1_comentarios_calificacion,'') AS p1_comentarios_calificacion, "
                . " IFNULL(C.p2_comentarios_calificacion,'') AS p2_comentarios_calificacion, "
                . " IFNULL(C.p3_comentarios_calificacion,'') AS p3_comentarios_calificacion, "
                . " IFNULL(C.p4_comentarios_calificacion,'') AS p4_comentarios_calificacion, "
                . " IFNULL(C.p5_comentarios_calificacion,'') AS p5_comentarios_calificacion, "
                . " IFNULL(C.p6_comentarios_calificacion,'') AS p6_comentarios_calificacion, "
                . " IFNULL(C.phab_comentarios_calificacion,'') AS phab_comentarios_calificacion, "
                . " IFNULL(C.pfin_comentarios_calificacion,'') AS pfin_comentarios_calificacion, "
                . " IFNULL((SELECT lcp1.descripcion_logro FROM LogrosAsignaturasApp lcp1 WHERE lcp1.id_logro=C.p1_logroc_calificacion),'') AS p1_descripcion_logroc, "
                . " IFNULL((SELECT lpp1.descripcion_logro FROM LogrosAsignaturasApp lpp1 WHERE lpp1.id_logro=C.p1_logrop_calificacion),'') AS p1_descripcion_logrop, "
                . " IFNULL((SELECT lap1.descripcion_logro FROM LogrosAsignaturasApp lap1 WHERE lap1.id_logro=C.p1_logroa_calificacion),'') AS p1_descripcion_logroa, "
                . " IFNULL((SELECT lcp2.descripcion_logro FROM LogrosAsignaturasApp lcp2 WHERE lcp2.id_logro=C.p2_logroc_calificacion),'') AS p2_descripcion_logroc, "
                . " IFNULL((SELECT lpp2.descripcion_logro FROM LogrosAsignaturasApp lpp2 WHERE lpp2.id_logro=C.p2_logrop_calificacion),'') AS p2_descripcion_logrop, "
                . " IFNULL((SELECT lap2.descripcion_logro FROM LogrosAsignaturasApp lap2 WHERE lap2.id_logro=C.p2_logroa_calificacion),'') AS p2_descripcion_logroa, "
                . " IFNULL((SELECT lcp3.descripcion_logro FROM LogrosAsignaturasApp lcp3 WHERE lcp3.id_logro=C.p3_logroc_calificacion),'') AS p3_descripcion_logroc, "
                . " IFNULL((SELECT lpp3.descripcion_logro FROM LogrosAsignaturasApp lpp3 WHERE lpp3.id_logro=C.p3_logrop_calificacion),'') AS p3_descripcion_logrop, "
                . " IFNULL((SELECT lap3.descripcion_logro FROM LogrosAsignaturasApp lap3 WHERE lap3.id_logro=C.p3_logroa_calificacion),'') AS p3_descripcion_logroa, "
                . " IFNULL((SELECT lcp4.descripcion_logro FROM LogrosAsignaturasApp lcp4 WHERE lcp4.id_logro=C.p4_logroc_calificacion),'') AS p4_descripcion_logroc, "
                . " IFNULL((SELECT lpp4.descripcion_logro FROM LogrosAsignaturasApp lpp4 WHERE lpp4.id_logro=C.p4_logrop_calificacion),'') AS p4_descripcion_logrop, "
                . " IFNULL((SELECT lap4.descripcion_logro FROM LogrosAsignaturasApp lap4 WHERE lap4.id_logro=C.p4_logroa_calificacion),'') AS p4_descripcion_logroa, "
                . " IFNULL((SELECT lcp5.descripcion_logro FROM LogrosAsignaturasApp lcp5 WHERE lcp5.id_logro=C.p5_logroc_calificacion),'') AS p5_descripcion_logroc, "
                . " IFNULL((SELECT lpp5.descripcion_logro FROM LogrosAsignaturasApp lpp5 WHERE lpp5.id_logro=C.p5_logrop_calificacion),'') AS p5_descripcion_logrop, "
                . " IFNULL((SELECT lap5.descripcion_logro FROM LogrosAsignaturasApp lap5 WHERE lap5.id_logro=C.p5_logroa_calificacion),'') AS p5_descripcion_logroa, "
                . " IFNULL((SELECT lcp6.descripcion_logro FROM LogrosAsignaturasApp lcp6 WHERE lcp6.id_logro=C.p6_logroc_calificacion),'') AS p6_descripcion_logroc, "
                . " IFNULL((SELECT lpp6.descripcion_logro FROM LogrosAsignaturasApp lpp6 WHERE lpp6.id_logro=C.p6_logrop_calificacion),'') AS p6_descripcion_logrop, "
                . " IFNULL((SELECT lap6.descripcion_logro FROM LogrosAsignaturasApp lap6 WHERE lap6.id_logro=C.p6_logroa_calificacion),'') AS p6_descripcion_logroa, "
                . " IFNULL((SELECT lcphab.descripcion_logro FROM LogrosAsignaturasApp lcphab WHERE lcphab.id_logro=C.phab_logroc_calificacion),'') AS phab_descripcion_logroc, "
                . " IFNULL((SELECT lpphab.descripcion_logro FROM LogrosAsignaturasApp lpphab WHERE lpphab.id_logro=C.phab_logrop_calificacion),'') AS phab_descripcion_logrop, "
                . " IFNULL((SELECT laphab.descripcion_logro FROM LogrosAsignaturasApp laphab WHERE laphab.id_logro=C.phab_logroa_calificacion),'') AS phab_descripcion_logroa, "
                . " ROUND(IFNULL((IFNULL(C.p1_nd_calificacion,0)*" . $porcP1 . " + IFNULL(C.p2_nd_calificacion,0)*" . $porcP2 . " + IFNULL(C.p3_nd_calificacion,0)*" . $porcP3 . " + IFNULL(C.p4_nd_calificacion,0)*" . $porcP4 . " + IFNULL(C.p5_nd_calificacion,0)*" . $porcP5 . " + IFNULL(C.p6_nd_calificacion,0)*" . $porcP6 . " ),'0'),1) AS def"
                . " FROM (SELECT @rownum :=0) R, "
                . " MatriculaAsignaturasApp MA "
                . " INNER JOIN AsignaturasApp A ON MA.id_asignatura=A.id_asignatura "
                . " INNER JOIN MatriculasApp M ON MA.id_matricula=M.id_matricula "
                . " INNER JOIN ObservadorEstudianteApp OE ON MA.id_estudiante=OE.id_estudiante "
                . " LEFT JOIN PlanEstudioDetalleApp PED ON MA.id_planestudiodetalle=PED.id_planestudiodetalle "
                . " LEFT JOIN CalificacionesApp C ON MA.id_matasig=C.id_matasig "
                . " LEFT JOIN AreasApp AR ON A.id_area=AR.id_area "
                . " WHERE MA.status_matriculaasignatura=1 "
                . " AND M.estado_matricula!='Retirado' AND M.estado_matricula!='Finalizado' "
                . " AND M.status_matricula=1 "
                . " AND OE.status_estudiante=1 "
        ;
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND M.id_escuela=:p_id_escuela ";
        }
        if ($idsede !== null) {
            $arraywhere['p_id_sede'] = $idsede;
            $sql = $sql . " AND M.id_sede=:p_id_sede ";
        }
        if ($idjornada !== null) {
            $arraywhere['p_id_jornada'] = $idjornada;
            $sql = $sql . " AND M.id_jornada=:p_id_jornada ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND MA.id_programa=:p_id_programa ";
        }
        if ($idplanestudio !== null) {
            $arraywhere['p_id_planestudio'] = $idplanestudio;
            $sql = $sql . " AND MA.id_planestudio=:p_id_planestudio ";
        }
        if ($grado !== null) {
            $arraywhere['p_num_grado'] = $grado;
            $sql = $sql . " AND MA.numgrado_programa=:p_num_grado ";
        }
        if ($idgrupo !== null) {
            $arraywhere['p_id_grupo'] = $idgrupo;
            $sql = $sql . " AND MA.id_grupo=:p_id_grupo ";
        }
        if ($idperiodo !== null) {
            $arraywhere['p_id_periodo'] = $idperiodo;
            $sql = $sql . " AND MA.id_periodo=:p_id_periodo ";
        }
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND MA.id_estudiante=:p_id_estudiante ";
        }
        if ($idmatricula !== null) {
            $arraywhere['p_id_matricula'] = $idmatricula;
            $sql = $sql . " AND MA.id_matricula=:p_id_matricula ";
        }
        $sql = $sql . " ORDER BY OE.nombrecompleto_estudiante, AR.nombre_area ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getInformesCalificaciones($idescuela = null, $idsede = null, $idjornada = null, $idprograma = null, $idplanestudio = null, $grado = null, $idgrupo = null, $idperiodo = null, $idestudiante = null, $idmatricula = null) {
        $resultEstudiantes = null;
        $resultCalificaciones = null;
        $resultEstudiantes = $this->getEstudiantesMatriculas($idescuela, $idsede, $idjornada, $idprograma, $idplanestudio, $grado, $idgrupo, $idperiodo, $idestudiante, $idmatricula);
        if ($resultEstudiantes !== null && $resultEstudiantes !== '[]') {
            $resultEstudiantes = json_decode($resultEstudiantes, true);
        }
        if ($resultEstudiantes !== null && is_array($resultEstudiantes)) {
            for ($i = 0; $i < count($resultEstudiantes); $i++) {
                $resultCalificaciones = $this->getCalificaciones($resultEstudiantes[$i]['id_escuela'], $resultEstudiantes[$i]['id_sede'], $resultEstudiantes[$i]['id_jornada'], $resultEstudiantes[$i]['id_programa'], $resultEstudiantes[$i]['id_planestudio'], $resultEstudiantes[$i]['numgrado_programa'], $resultEstudiantes[$i]['id_grupo'], $resultEstudiantes[$i]['id_periodo'], $resultEstudiantes[$i]['id_estudiante'], $resultEstudiantes[$i]['id_matricula']);
                if ($resultCalificaciones !== null) {
                    $resultEstudiantes[$i]['calificaciones'] = $resultCalificaciones;
                }
            }
        }
        $resultEstudiantes = json_encode($resultEstudiantes);
        return $resultEstudiantes;
    }

    public function getObservadorEstudiante($idescuela, $idestudiante) {
        $resultEstudiantes = null;
        $resultCalificaciones = null;
        $resultAnotaciones = null;
        $resultEstudiantes = $this->getEstudiantes($idestudiante);
        if ($resultEstudiantes !== null && $resultEstudiantes !== '[]') {
            $resultEstudiantes = json_decode($resultEstudiantes, true);
        }
        if ($resultEstudiantes !== null && is_array($resultEstudiantes)) {
            for ($i = 0; $i < count($resultEstudiantes); $i++) {
                $resultCalificaciones = $this->getCalificaciones($idescuela, null, null, null, null, null, null, null, $resultEstudiantes[$i]['id_estudiante'], null);
                if ($resultCalificaciones !== null) {
                    $resultEstudiantes[$i]['calificaciones'] = $resultCalificaciones;
                }
                $resultAnotaciones = $this->getAnotaciones($resultEstudiantes[$i]['id_estudiante']);
                if ($resultAnotaciones !== null) {
                    $resultEstudiantes[$i]['anotaciones'] = $resultAnotaciones;
                }
            }
        }
        $resultEstudiantes = json_encode($resultEstudiantes);
        return $resultEstudiantes;
    }

    public function getValoresPecuniarios($idescuela = null, $idpecuniario = null, $tipopecuniario = null, $anualidad = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM ValoresPecuniariosApp WHERE status_pecuniario=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($idpecuniario !== null) {
            $arraywhere['p_id_pecuniario'] = $idpecuniario;
            $sql = $sql . " AND id_pecuniario=:p_id_pecuniario ";
        }
        if ($tipopecuniario !== null) {
            $arraywhere['p_tipo_pecuniario'] = $tipopecuniario;
            $sql = $sql . " AND tipo_pecuniario=:p_tipo_pecuniario ";
        }
        if ($anualidad !== null) {
            $arraywhere['p_anualidad_pecuniario'] = $anualidad;
            $sql = $sql . " AND anualidad_pecuniario=:p_anualidad_pecuniario ";
        }
        $sql = $sql . " ORDER BY anualidad_pecuniario DESC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getPagosRecibidos($idescuela = null, $idestudiante = null, $idpecuniario = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM PagosRecibidosApp WHERE status_pago=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND id_estudiante=:p_id_estudiante ";
        }
        if ($idpecuniario !== null) {
            $arraywhere['p_id_pecuniario'] = $idpecuniario;
            $sql = $sql . " AND id_pecuniario=:p_id_pecuniario ";
        }
        $sql = $sql . " ORDER BY fecha_pago DESC ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

}
