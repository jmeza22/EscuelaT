<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * AND open the template in the editor.
 */

/**
 * Description of BancoReportes
 *
 * @author JOSE MEZA
 */
class ReportsBank extends BasicController {

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

    public function getProgramas($idescuela = null, $idprograma = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM ProgramasApp WHERE status_programa=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND id_programa=:p_id_programa ";
        }
        $sql = $sql . " ORDER BY nombre_programa ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getPlanEstudio($idescuela = null, $idprograma = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM PlanEstudiosApp WHERE status_planestudio=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND id_programa=:p_id_programa ";
        }
        $sql = $sql . " ORDER BY id_planestudio ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getPlanEstudioDetalle($idescuela = null, $idprograma = null, $numgrado = null, $idplanestudio = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT PED.*, A.nombre_asignatura, PE.id_programa, P.nombre_programa "
                . " FROM PlanEstudioDetalleApp PED "
                . " INNER JOIN PlanEstudiosApp PE ON PED.id_planestudio=PE.id_planestudio "
                . " INNER JOIN AsignaturasApp A ON PED.id_asignatura=A.id_asignatura "
                . " INNER JOIN ProgramasApp P ON PE.id_programa=P.id_programa "
                . " WHERE PED.status_planestudiodetalle=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND PE.id_escuela=:p_id_escuela ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND PE.id_programa=:p_id_programa ";
        }
        if ($numgrado !== null) {
            $arraywhere['p_numgrado_programa'] = $numgrado;
            $sql = $sql . " AND PED.numgrado_programa=:p_numgrado_programa ";
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
                    $plan[$i]['detalle'] = $this->getPlanEstudioDetalle($plan['id_escuela'], $plan['id_programa'], null, $plan['id_planestudio']);
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

    public function getGrupos($idescuela = null, $idprograma = null, $numgrado = null, $idgrupo = null, $diferenciador = null) {
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
        if ($idgrupo !== null) {
            $arraywhere['p_id_grupo'] = $idgrupo;
            $sql = $sql . " AND G.id_grupo=:p_id_grupo ";
        }
        if ($diferenciador !== null) {
            $arraywhere['p_num_grupo'] = $diferenciador;
            $sql = $sql . " AND G.num_grupo=:p_num_grupo ";
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

    public function getPeriodosAnualesSiguientes($idescuela, $anoactual) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM PeriodosAnualesApp WHERE status_periodo=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($anoactual !== null) {
            $arraywhere['p_ano_actual'] = $anoactual;
            $sql = $sql . " AND anualidad_periodo > :p_ano_actual ";
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

    public function getPersonas($tipopersona, $nombre1 = null, $apellido1 = null, $sexo = null, $idpersona = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM PersonasApp "
                . "WHERE status_persona=1 ";
        $arraywhere = Array();
        if ($tipopersona !== null) {
            $arraywhere['p_tipo_persona'] = $tipopersona;
            $sql = $sql . " AND tipo_persona=:p_tipo_persona ";
        }
        if ($nombre1 !== null) {
            $arraywhere['p_nombre1_persona'] = $nombre1;
            $sql = $sql . " AND nombre1_persona=:p_nombre1_persona ";
        }
        if ($apellido1 !== null) {
            $arraywhere['p_apellido1_persona'] = $apellido1;
            $sql = $sql . " AND apellido1_persona=:p_apellido1_persona ";
        }
        if ($sexo !== null) {
            $arraywhere['p_sexo_persona'] = $sexo;
            $sql = $sql . " AND sexo_persona=:p_sexo_persona ";
        }
        if ($idpersona !== null) {
            $arraywhere['p_id_persona'] = $idpersona;
            $sql = $sql . " AND id_persona=:p_id_persona ";
        }
        $sql = $sql . "ORDER BY num_persona DESC";
        $result = $this->selectJSONArray($sql, $arraywhere);
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
        $sql = "SELECT D.*, IFNULL(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(P.fechanacimiento_persona)), '%Y')+0,'?') AS edad_persona, "
                . " P.tipodoc_persona, P.documento_persona, P.fechanacimiento_persona, P.sexo_persona "
                . " FROM DocentesApp D INNER JOIN PersonasApp P ON D.id_docente=P.id_persona "
                . " WHERE D.status_docente=1 ";
        $arraywhere = Array();
        if ($iddocente !== null) {
            $arraywhere['p_id_docente'] = $iddocente;
            $sql = $sql . " AND D.id_docente=:p_id_docente ";
        }
        $sql = $sql . "ORDER BY D.nombrecompleto_docente ASC";
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
        $sql = $sql . " ORDER BY C.id_periodo DESC, D.nombrecompleto_docente, CAST(C.numgrado_programa AS DECIMAL), A.nombre_asignatura ";
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
        $sql = "SELECT P.*, IFNULL(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(P.fechanacimiento_persona)), '%Y')+0,'?') AS edad_persona  "
                . " FROM PersonasApp P "
                . " WHERE P.status_persona=1 AND P.tipo_persona='Attendant' ";
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND P.id_estudiante=:p_id_estudiante ";
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

    public function getMatriculas($idescuela = null, $idsede = null, $idjornada = null, $idprograma = null, $idplanestudio = null, $numgrado = null, $idgrupo = null, $idperiodo = null, $idestudiante = null, $idmatricula = null, $fechamatricula = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT M.*, P.*, E.nombre_escuela, S.nombre_sede, J.nombre_jornada, Pr.nombre_programa, PE.descripcion_planestudio "
                . " FROM MatriculasApp M "
                . " INNER JOIN EscuelasApp E ON E.id_escuela=M.id_escuela"
                . " INNER JOIN SedesApp S ON S.id_sede=M.id_sede"
                . " INNER JOIN JornadasApp J ON J.id_jornada=M.id_jornada"
                . " INNER JOIN ProgramasApp Pr ON Pr.id_programa=M.id_programa"
                . " INNER JOIN PlanEstudiosApp PE ON PE.id_programa=M.id_programa"
                . " INNER JOIN PersonasApp P ON P.id_persona=M.id_estudiante"
                . " WHERE M.status_matricula=1 ";
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
        if ($numgrado !== null) {
            $arraywhere['p_num_grado'] = $numgrado;
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
        if ($fechamatricula !== null) {
            $arraywhere['p_fecha_matricula'] = $fechamatricula;
            $sql = $sql . " AND fecha_matricula=:p_fecha_matricula ";
        }
        $sql = $sql . " ORDER BY M.id_programa, CAST(M.numgrado_programa AS DECIMAL), M.id_grupo, M.nombrecompleto_estudiante, M.fecha_matricula DESC  ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getContactosDocentes($iddocente = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT D.id_docente, D.nombrecompleto_docente, P.nombre1_persona, P.apellido1_persona, P.tipodoc_persona, P.documento_persona, P.telefono_persona, P.email_persona, P.direccion_persona, P.ciudad_persona "
                . " FROM DocentesApp D INNER JOIN PersonasApp P ON D.id_docente=P.id_persona "
                . " WHERE D.status_docente=1 ";
        $arraywhere = Array();
        if ($iddocente !== null) {
            $arraywhere['p_id_docente'] = $iddocente;
            $sql = $sql . " AND D.id_docente=:p_id_docente ";
        }
        $sql = $sql . " ORDER BY D.nombrecompleto_docente ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getContactosEstudiantesMatriculas($idescuela = null, $idprograma = null, $numgrado = null, $idgrupo = null, $idperiodo = null, $idestudiante = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT M.id_periodo, M.id_programa, Pr.nombre_programa, M.numgrado_programa, M.id_grupo, M.id_estudiante, M.nombrecompleto_estudiante, P.email_persona, P.telefono_persona, P.direccion_persona, P.ciudad_persona, OE.telefonoacudiente1_estudiante, OE.telefonoacudiente2_estudiante "
                . " FROM MatriculasApp M INNER JOIN PersonasApp P ON M.id_estudiante=P.id_persona INNER JOIN ObservadorEstudianteApp OE ON M.id_estudiante=OE.id_estudiante INNER JOIN ProgramasApp Pr ON M.id_programa=Pr.id_programa "
                . " WHERE M.status_matricula=1 AND M.estado_matricula!='Retirado' AND M.estado_matricula!='Anulado' AND M.estado_matricula!='Finalizado' ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND M.id_escuela=:p_id_escuela ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND M.id_programa=:p_id_programa ";
        }
        if ($numgrado !== null) {
            $arraywhere['p_num_grado'] = $numgrado;
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
        $sql = $sql . " ORDER BY CAST(M.numgrado_programa AS DECIMAL), M.nombrecompleto_estudiante ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getAsignaturasMatriculadas($idescuela = null, $idmatricula = null, $idestudiante = null, $idprograma = null, $idasignatura = null, $idperiodo = null, $grado = null, $idgrupo = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT MA.*, A.nombre_asignatura, M.nombrecompleto_estudiante, PED.hteoricas_asignatura, PED.hpracticas_asignatura "
                . " FROM MatriculaAsignaturasApp MA "
                . " INNER JOIN MatriculasApp M ON MA.id_matricula=M.id_matricula "
                . " INNER JOIN ObservadorEstudianteApp OE ON M.id_estudiante=OE.id_estudiante "
                . " INNER JOIN PlanEstudiosApp PE ON M.id_planestudio=PE.id_planestudio "
                . " INNER JOIN PlanEstudioDetalleApp PED ON PE.id_planestudio=PED.id_planestudio "
                . " INNER JOIN AsignaturasApp A ON MA.id_asignatura=A.id_asignatura "
                . " WHERE MA.id_asignatura=PED.id_asignatura AND M.numgrado_programa=PED.numgrado_programa "
                . " AND MA.status_matriculaasignatura=1 AND M.status_matricula=1 AND A.status_asignatura=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND M.id_escuela=:p_id_escuela ";
        }
        if ($idmatricula !== null) {
            $arraywhere['p_id_matricula'] = $idmatricula;
            $sql = $sql . " AND MA.id_matricula=:p_id_matricula ";
        }
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND M.id_estudiante=:p_id_estudiante ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND M.id_programa=:p_id_programa ";
        }
        if ($idasignatura !== null) {
            $arraywhere['p_id_asignatura'] = $idasignatura;
            $sql = $sql . " AND MA.id_asignatura=:p_id_asignatura ";
        }
        if ($idperiodo !== null) {
            $arraywhere['p_id_periodo'] = $idperiodo;
            $sql = $sql . " AND M.id_periodo=:p_id_periodo ";
        }
        if ($grado !== null) {
            $arraywhere['p_num_grado'] = $grado;
            $sql = $sql . " AND M.numgrado_programa=:p_num_grado ";
        }
        if ($idgrupo !== null) {
            $arraywhere['p_id_grupo'] = $idgrupo;
            $sql = $sql . " AND M.id_grupo=:p_id_grupo ";
        }
        $sql = $sql . " ORDER BY M.nombrecompleto_estudiante, MA.id_asignatura";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getAsistencias($idescuela = null, $idmatricula = null, $idestudiante = null, $idprograma = null, $idasignatura = null, $idperiodo = null, $grado = null, $idgrupo = null, $fecha = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT @rownum := @rownum +1 AS rownum, M.*, MA.id_matasig, MA.id_asignatura, A.nombre_asignatura, M.nombrecompleto_estudiante, Asi.* "
                . "FROM (SELECT @rownum :=0) R, MatriculaAsignaturasApp MA "
                . "INNER JOIN MatriculasApp M ON MA.id_matricula=M.id_matricula "
                . "INNER JOIN AsignaturasApp A ON MA.id_asignatura=A.id_asignatura "
                . "INNER JOIN AsistenciaApp Asi ON MA.id_matasig=Asi.id_matasig "
                . "WHERE MA.status_matriculaasignatura=1 AND A.status_asignatura=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND M.id_escuela=:p_id_escuela ";
        }
        if ($idmatricula !== null) {
            $arraywhere['p_id_matricula'] = $idmatricula;
            $sql = $sql . " AND M.id_matricula=:p_id_matricula ";
        }
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND M.id_estudiante=:p_id_estudiante ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND M.id_programa=:p_id_programa ";
        }
        if ($idasignatura !== null) {
            $arraywhere['p_id_asignatura'] = $idasignatura;
            $sql = $sql . " AND MA.id_asignatura=:p_id_asignatura ";
        }
        if ($idperiodo !== null) {
            $arraywhere['p_id_periodo'] = $idperiodo;
            $sql = $sql . " AND M.id_periodo=:p_id_periodo ";
        }
        if ($grado !== null) {
            $arraywhere['p_num_grado'] = $grado;
            $sql = $sql . " AND M.numgrado_programa=:p_num_grado ";
        }
        if ($idgrupo !== null) {
            $arraywhere['p_id_grupo'] = $idgrupo;
            $sql = $sql . " AND M.id_grupo=:p_id_grupo ";
        }
        if ($fecha !== null) {
            $arraywhere['p_fecha_asistencia'] = $fecha;
            $sql = $sql . " AND Asi.fecha_asistencia=:p_fecha_asistencia ";
        }
        $sql = $sql . " ORDER BY M.nombrecompleto_estudiante";
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
                . "IFNULL(DG.nombrecompleto_docente,'') AS nombre_director, "
                . " CASE Cn.tipopromedio_configuracion "
                . " WHEN 'PONDERADO' THEN "
                . " ROUND("
                . " SUM(("
                . " (IFNULL(C.p1_nd_calificacion,0)*(IFNULL(Cn.p1_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p2_nd_calificacion,0)*(IFNULL(Cn.p2_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p3_nd_calificacion,0)*(IFNULL(Cn.p3_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p4_nd_calificacion,0)*(IFNULL(Cn.p4_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p5_nd_calificacion,0)*(IFNULL(Cn.p5_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p6_nd_calificacion,0)*(IFNULL(Cn.p6_porcentaje_configuracion,0)/100)) "
                . " ) * A.numcreditos_asignatura) / SUM(IFNULL(A.numcreditos_asignatura,0)),3) "
                . " WHEN 'ARITMETICO' THEN "
                . " ROUND(AVG("
                . " (IFNULL(C.p1_nd_calificacion,0)*(IFNULL(Cn.p1_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p2_nd_calificacion,0)*(IFNULL(Cn.p2_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p3_nd_calificacion,0)*(IFNULL(Cn.p3_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p4_nd_calificacion,0)*(IFNULL(Cn.p4_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p5_nd_calificacion,0)*(IFNULL(Cn.p5_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p6_nd_calificacion,0)*(IFNULL(Cn.p6_porcentaje_configuracion,0)/100)) "
                . "),3) END AS Promedio, "
                . " (SELECT COUNT(C0.pfin_nd_calificacion) AS cantidadRep FROM CalificacionesApp C0 INNER JOIN ConfiguracionApp Cn ON C0.id_escuela=Cn.id_escuela WHERE C0.id_matricula=C.id_matricula AND C0.status_calificacion=1 AND C0.pfin_nd_calificacion < Cn.valaprueba_configuracion ) AS CantidadReprobadas "
                . " FROM "
                . " ObservadorEstudianteApp OE "
                . " INNER JOIN PersonasApp P ON OE.id_estudiante=P.id_persona "
                . " INNER JOIN MatriculasApp M ON OE.id_estudiante=M.id_estudiante "
                . " INNER JOIN ProgramasApp Pr ON M.id_programa=Pr.id_programa "
                . " INNER JOIN PeriodosAnualesApp Pe ON M.id_periodo=Pe.id_periodo "
                . " INNER JOIN GruposApp G ON M.id_grupo=G.id_grupo "
                . " INNER JOIN ConfiguracionApp Cn ON M.id_escuela=Cn.id_escuela "
                . " LEFT JOIN CalificacionesApp C ON C.id_matricula=M.id_matricula "
                . " LEFT JOIN AsignaturasApp A ON C.id_asignatura=A.id_asignatura "
                . " LEFT JOIN SedesApp S ON M.id_sede=S.id_sede "
                . " LEFT JOIN JornadasApp J ON M.id_jornada=J.id_jornada "
                . " LEFT JOIN DirectoresGruposApp DG ON M.id_grupo=DG.id_grupo "
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
        $sql = $sql . " GROUP BY M.id_estudiante, M.id_matricula ORDER BY M.id_programa, CAST(M.numgrado_programa AS DECIMAL), M.id_grupo, Promedio DESC, M.nombrecompleto_estudiante ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getCalificaciones($idescuela = null, $idsede = null, $idjornada = null, $idprograma = null, $idplanestudio = null, $grado = null, $idgrupo = null, $idperiodo = null, $idestudiante = null, $idmatricula = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT @rownum := @rownum +1 AS rownum, "
                . " M.*, "
                . " C.id_calificacion AS id_calificacion,"
                . " MA.id_asignatura, "
                . " A.nombre_asignatura, "
                . " AR.nombre_area, "
                . " PED.hteoricas_asignatura AS hteoricas_asignatura, "
                . " PED.hpracticas_asignatura AS hpracticas_asignatura, "
                . " IFNULL(C.p1_nd_calificacion,'') AS p1_nd_calificacion, "
                . " IFNULL(C.p2_nd_calificacion,'') AS p2_nd_calificacion, "
                . " IFNULL(C.p3_nd_calificacion,'') AS p3_nd_calificacion, "
                . " IFNULL(C.p4_nd_calificacion,'') AS p4_nd_calificacion, "
                . " IFNULL(C.p5_nd_calificacion,'') AS p5_nd_calificacion, "
                . " IFNULL(C.p6_nd_calificacion,'') AS p6_nd_calificacion, "
                . " IFNULL(C.phab_nd_calificacion,'') AS phab_nd_calificacion, "
                . " ROUND(IFNULL(C.pfin_nd_calificacion,''),1) AS pfin_nd_calificacion, "
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
                . " ROUND(IFNULL(("
                . " (IFNULL(C.p1_nd_calificacion,0)*(IFNULL(Cn.p1_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p2_nd_calificacion,0)*(IFNULL(Cn.p2_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p3_nd_calificacion,0)*(IFNULL(Cn.p3_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p4_nd_calificacion,0)*(IFNULL(Cn.p4_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p5_nd_calificacion,0)*(IFNULL(Cn.p5_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p6_nd_calificacion,0)*(IFNULL(Cn.p6_porcentaje_configuracion,0)/100)) "
                . " ),'0'),1) AS def "
                . " FROM (SELECT @rownum :=0) R, "
                . " MatriculasApp M "
                . " INNER JOIN ObservadorEstudianteApp OE ON OE.id_estudiante=M.id_estudiante "
                . " INNER JOIN MatriculaAsignaturasApp MA ON MA.id_matricula=M.id_matricula "
                . " INNER JOIN ConfiguracionApp Cn ON Cn.id_escuela=M.id_escuela "
                . " INNER JOIN AsignaturasApp A ON A.id_asignatura=MA.id_asignatura "
                . " LEFT JOIN PlanEstudioDetalleApp PED ON MA.id_planestudiodetalle=PED.id_planestudiodetalle "
                . " LEFT JOIN CalificacionesApp C ON MA.id_matasig=C.id_matasig "
                . " LEFT JOIN AreasApp AR ON A.id_area=AR.id_area "
                . " WHERE MA.status_matriculaasignatura=1 "
                . " AND M.estado_matricula!='Retirado' AND M.estado_matricula!='Anulado' "
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
        $sql = $sql . " ORDER BY M.id_escuela, M.id_programa, M.numgrado_programa, M.id_grupo, M.nombrecompleto_estudiante, AR.nombre_area, A.nombre_asignatura ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getCalificacionesRendimientoBajo($idescuela = null, $idprograma = null, $idplanestudio = null, $grado = null, $idgrupo = null, $idperiodo = null, $idestudiante = null, $idmatricula = null) {
        $sql = " SELECT OE.nombrecompleto_estudiante, A.nombre_asignatura, Ar.nombre_area, Pr.nombre_programa, Cn.valaprueba_configuracion, C.*,  "
                . " ROUND(IFNULL(("
                . " (IFNULL(C.p1_nd_calificacion,0)*(IFNULL(Cn.p1_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p2_nd_calificacion,0)*(IFNULL(Cn.p2_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p3_nd_calificacion,0)*(IFNULL(Cn.p3_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p4_nd_calificacion,0)*(IFNULL(Cn.p4_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p5_nd_calificacion,0)*(IFNULL(Cn.p5_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p6_nd_calificacion,0)*(IFNULL(Cn.p6_porcentaje_configuracion,0)/100)) "
                . " ),'0'),1) AS definitiva "
                . " FROM CalificacionesApp C "
                . " INNER JOIN ConfiguracionApp Cn ON C.id_escuela=Cn.id_escuela "
                . " INNER JOIN ObservadorEstudianteApp OE ON C.id_estudiante=OE.id_estudiante "
                . " INNER JOIN AsignaturasApp A ON C.id_asignatura=A.id_asignatura "
                . " INNER JOIN AreasApp Ar ON A.id_area=Ar.id_area "
                . " INNER JOIN ProgramasApp Pr ON C.id_programa=Pr.id_programa "
                . " INNER JOIN MatriculasApp M ON C.id_matricula=M.id_matricula "
                . " WHERE C.status_calificacion=1 AND A.status_asignatura=1 AND M.estado_matricula!='Retirado' AND M.estado_matricula!='Anulado' "
                . " AND ( "
                . " IFNULL(C.p1_nd_calificacion, Cn.valaprueba_configuracion) < Cn.valaprueba_configuracion OR "
                . " IFNULL(C.p2_nd_calificacion, Cn.valaprueba_configuracion) < Cn.valaprueba_configuracion OR "
                . " IFNULL(C.p3_nd_calificacion, Cn.valaprueba_configuracion) < Cn.valaprueba_configuracion OR "
                . " IFNULL(C.p4_nd_calificacion, Cn.valaprueba_configuracion) < Cn.valaprueba_configuracion OR "
                . " IFNULL(C.p5_nd_calificacion, Cn.valaprueba_configuracion) < Cn.valaprueba_configuracion OR "
                . " IFNULL(C.p6_nd_calificacion, Cn.valaprueba_configuracion) < Cn.valaprueba_configuracion "
                . " ) "
                . " AND ("
                . " (IFNULL(C.p1_nd_calificacion, Cn.valaprueba_configuracion)*(IFNULL(Cn.p1_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p2_nd_calificacion, Cn.valaprueba_configuracion)*(IFNULL(Cn.p2_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p3_nd_calificacion, Cn.valaprueba_configuracion)*(IFNULL(Cn.p3_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p4_nd_calificacion, Cn.valaprueba_configuracion)*(IFNULL(Cn.p4_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p5_nd_calificacion, Cn.valaprueba_configuracion)*(IFNULL(Cn.p5_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p6_nd_calificacion, Cn.valaprueba_configuracion)*(IFNULL(Cn.p6_porcentaje_configuracion,0)/100)) "
                . " ) < Cn.valaprueba_configuracion ";

        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND C.id_escuela=:p_id_escuela ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND C.id_programa=:p_id_programa ";
        }
        if ($idplanestudio !== null) {
            $arraywhere['p_id_planestudio'] = $idplanestudio;
            $sql = $sql . " AND C.id_planestudio=:p_id_planestudio ";
        }
        if ($grado !== null) {
            $arraywhere['p_num_grado'] = $grado;
            $sql = $sql . " AND C.numgrado_programa=:p_num_grado ";
        }
        if ($idgrupo !== null) {
            $arraywhere['p_id_grupo'] = $idgrupo;
            $sql = $sql . " AND C.id_grupo=:p_id_grupo ";
        }
        if ($idperiodo !== null) {
            $arraywhere['p_id_periodo'] = $idperiodo;
            $sql = $sql . " AND C.id_periodo=:p_id_periodo ";
        }
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND C.id_estudiante=:p_id_estudiante ";
        }
        if ($idmatricula !== null) {
            $arraywhere['p_id_matricula'] = $idmatricula;
            $sql = $sql . " AND C.id_matricula=:p_id_matricula ";
        }
        $sql = $sql . " ORDER BY C.id_periodo, OE.nombrecompleto_estudiante, AR.nombre_area, A.nombre_asignatura ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getCantidadAsignaturasReprobadas($idescuela = null, $idprograma = null, $idplanestudio = null, $grado = null, $idgrupo = null, $idperiodo = null, $idestudiante = null, $idmatricula = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT M.*, Pr.nombre_programa, OE.foto_estudiante, "
                . " (SELECT COUNT(C0.pfin_nd_calificacion) AS cantidadRep FROM CalificacionesApp C0 INNER JOIN ConfiguracionApp Cn ON C0.id_escuela=Cn.id_escuela WHERE C0.id_matricula=C.id_matricula AND C0.status_calificacion=1 AND C0.pfin_nd_calificacion < Cn.valaprueba_configuracion ) AS CantidadReprobadas "
                . " FROM CalificacionesApp C "
                . " INNER JOIN ConfiguracionApp Cn ON C.id_escuela=Cn.id_escuela "
                . " INNER JOIN ObservadorEstudianteApp OE ON C.id_estudiante=OE.id_estudiante "
                . " INNER JOIN MatriculasApp M ON C.id_matricula=M.id_matricula "
                . " INNER JOIN ProgramasApp Pr ON C.id_programa=Pr.id_programa "
                . " WHERE C.status_calificacion=1 AND M.status_matricula=1 AND M.estado_matricula!='Retirado' AND M.estado_matricula!='Anulado' ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND C.id_escuela=:p_id_escuela ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND C.id_programa=:p_id_programa ";
        }
        if ($idplanestudio !== null) {
            $arraywhere['p_id_planestudio'] = $idplanestudio;
            $sql = $sql . " AND C.id_planestudio=:p_id_planestudio ";
        }
        if ($grado !== null) {
            $arraywhere['p_num_grado'] = $grado;
            $sql = $sql . " AND C.numgrado_programa=:p_num_grado ";
        }
        if ($idgrupo !== null) {
            $arraywhere['p_id_grupo'] = $idgrupo;
            $sql = $sql . " AND C.id_grupo=:p_id_grupo ";
        }
        if ($idperiodo !== null) {
            $arraywhere['p_id_periodo'] = $idperiodo;
            $sql = $sql . " AND C.id_periodo=:p_id_periodo ";
        }
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND C.id_estudiante=:p_id_estudiante ";
        }
        if ($idmatricula !== null) {
            $arraywhere['p_id_matricula'] = $idmatricula;
            $sql = $sql . " AND C.id_matricula=:p_id_matricula ";
        }
        $sql = $sql . " GROUP BY C.id_estudiante, C.id_matricula ORDER BY C.id_programa, C.numgrado_programa, C.id_grupo, OE.nombrecompleto_estudiante ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getPromedio($idescuela = null, $idprograma = null, $idplanestudio = null, $grado = null, $idgrupo = null, $idperiodo = null, $idestudiante = null, $idmatricula = null) {
        $sql = "SELECT M.*, Pr.nombre_programa, OE.foto_estudiante, "
                . " CASE IFNULL(Cn.tipopromedio_configuracion, 'ARITMETICO') "
                . " WHEN 'PONDERADO' THEN "
                . " ROUND("
                . " SUM(("
                . " (IFNULL(C.p1_nd_calificacion,0)*(IFNULL(Cn.p1_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p2_nd_calificacion,0)*(IFNULL(Cn.p2_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p3_nd_calificacion,0)*(IFNULL(Cn.p3_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p4_nd_calificacion,0)*(IFNULL(Cn.p4_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p5_nd_calificacion,0)*(IFNULL(Cn.p5_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p6_nd_calificacion,0)*(IFNULL(Cn.p6_porcentaje_configuracion,0)/100)) "
                . " ) * A.numcreditos_asignatura) / SUM(IFNULL(A.numcreditos_asignatura,0)),3) "
                . " WHEN 'ARITMETICO' THEN "
                . " ROUND(AVG("
                . " (IFNULL(C.p1_nd_calificacion,0)*(IFNULL(Cn.p1_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p2_nd_calificacion,0)*(IFNULL(Cn.p2_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p3_nd_calificacion,0)*(IFNULL(Cn.p3_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p4_nd_calificacion,0)*(IFNULL(Cn.p4_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p5_nd_calificacion,0)*(IFNULL(Cn.p5_porcentaje_configuracion,0)/100)) + "
                . " (IFNULL(C.p6_nd_calificacion,0)*(IFNULL(Cn.p6_porcentaje_configuracion,0)/100)) "
                . "),3) END AS Promedio "
                . " FROM CalificacionesApp C "
                . " INNER JOIN ConfiguracionApp Cn ON C.id_escuela=Cn.id_escuela "
                . " INNER JOIN ObservadorEstudianteApp OE ON C.id_estudiante=OE.id_estudiante "
                . " INNER JOIN AsignaturasApp A ON C.id_asignatura=A.id_asignatura "
                . " INNER JOIN ProgramasApp Pr ON C.id_programa=Pr.id_programa "
                . " INNER JOIN MatriculasApp M ON C.id_matricula=M.id_matricula "
                . " WHERE C.status_calificacion=1 AND A.status_asignatura=1 AND M.estado_matricula!='Retirado' AND M.estado_matricula!='Anulado' "
        ;
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND M.id_escuela=:p_id_escuela ";
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
        $sql = $sql . " GROUP BY M.id_estudiante, M.id_matricula ORDER BY M.id_programa, M.numgrado_programa, M.id_grupo, M.nombrecompleto_estudiante ";
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
                if ($resultCalificaciones !== null && $resultCalificaciones !== '[]') {
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

    public function getDatosEstadisticosBasicosEstudiantes($idescuela, $idprograma = null, $grado = null, $idgrupo = null, $idperiodo = null) {
        $sqlw = null;
        $result = null;
        $resultarray = Array();
        $sql1 = "SELECT COUNT(*) AS Total_Hombres, M.id_escuela, M.id_programa, M.id_planestudio, M.numgrado_programa, M.id_grupo, M.id_periodo FROM MatriculasApp M INNER JOIN PersonasApp P ON M.id_estudiante=P.id_persona WHERE M.status_matricula=1 AND M.estado_matricula!='Retirado' AND M.estado_matricula!='Anulado' AND P.sexo_persona='M' ";
        $sql2 = "SELECT COUNT(*) AS Total_Mujeres, M.id_escuela, M.id_programa, M.id_planestudio, M.numgrado_programa, M.id_grupo, M.id_periodo FROM MatriculasApp M INNER JOIN PersonasApp P ON M.id_estudiante=P.id_persona WHERE M.status_matricula=1 AND M.estado_matricula!='Retirado' AND M.estado_matricula!='Anulado' AND P.sexo_persona='F' ";
        $sql3 = "SELECT COUNT(*) AS Cantidad_Hombres, IFNULL(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(P.fechanacimiento_persona)), '%Y')+0,'?') AS Edad_Hombres, M.id_escuela, M.id_programa, M.id_planestudio, M.numgrado_programa, M.id_grupo, M.id_periodo FROM MatriculasApp M INNER JOIN PersonasApp P ON M.id_estudiante=P.id_persona WHERE M.status_matricula=1 AND M.estado_matricula!='Retirado' AND M.estado_matricula!='Anulado' AND P.sexo_persona='M' ";
        $sql4 = "SELECT COUNT(*) AS Cantidad_Mujeres, IFNULL(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(P.fechanacimiento_persona)), '%Y')+0,'?') AS Edad_Mujeres, M.id_escuela, M.id_programa, M.id_planestudio, M.numgrado_programa, M.id_grupo, M.id_periodo FROM MatriculasApp M INNER JOIN PersonasApp P ON M.id_estudiante=P.id_persona WHERE M.status_matricula=1 AND M.estado_matricula!='Retirado' AND M.estado_matricula!='Anulado' AND P.sexo_persona='F' ";

        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sqlw = $sqlw . " AND M.id_escuela=:p_id_escuela ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sqlw = $sqlw . " AND M.id_programa=:p_id_programa ";
        }
        if ($grado !== null) {
            $arraywhere['p_num_grado'] = $grado;
            $sqlw = $sqlw . " AND M.numgrado_programa=:p_num_grado ";
        }
        if ($idgrupo !== null) {
            $arraywhere['p_id_grupo'] = $idgrupo;
            $sqlw = $sqlw . " AND M.id_grupo=:p_id_grupo ";
        }
        if ($idperiodo !== null) {
            $arraywhere['p_id_periodo'] = $idperiodo;
            $sqlw = $sqlw . " AND M.id_periodo=:p_id_periodo ";
        }
        $sql1 = $sql1 . $sqlw;
        $sql2 = $sql2 . $sqlw;
        $sql3 = $sql3 . $sqlw;
        $sql4 = $sql4 . $sqlw;

        $sql1 = $sql1 . " GROUP BY M.id_escuela, M.id_programa, M.id_planestudio, M.numgrado_programa, M.id_grupo, M.id_periodo ORDER BY M.id_escuela, M.id_programa, M.numgrado_programa, M.id_grupo ";
        $sql2 = $sql2 . " GROUP BY M.id_escuela, M.id_programa, M.id_planestudio, M.numgrado_programa, M.id_grupo, M.id_periodo ORDER BY M.id_escuela, M.id_programa, M.numgrado_programa, M.id_grupo ";
        $sql3 = $sql3 . " GROUP BY Edad_Hombres, M.id_escuela, M.id_programa, M.id_planestudio, M.numgrado_programa, M.id_grupo, M.id_periodo ORDER BY M.id_escuela, M.id_programa, M.numgrado_programa, M.id_grupo, Edad_Hombres ";
        $sql4 = $sql4 . " GROUP BY Edad_Mujeres, M.id_escuela, M.id_programa, M.id_planestudio, M.numgrado_programa, M.id_grupo, M.id_periodo ORDER BY M.id_escuela, M.id_programa, M.numgrado_programa, M.id_grupo, Edad_Mujeres ";
        $resultarray['total_hombres'] = $this->selectJSONArray($sql1, $arraywhere);
        $resultarray['total_mujeres'] = $this->selectJSONArray($sql2, $arraywhere);
        $resultarray['rangos_hombres'] = $this->selectJSONArray($sql3, $arraywhere);
        $resultarray['rangos_mujeres'] = $this->selectJSONArray($sql4, $arraywhere);

        return $resultarray;
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

    public function getElecciones($idescuela = null, $idperiodo = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM EleccionesEstudiantilesApp WHERE status_eleccion=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($idperiodo !== null) {
            $arraywhere['p_id_periodo'] = $idperiodo;
            $sql = $sql . " AND id_periodo=:p_id_periodo ";
        }

        $sql = $sql . " ORDER BY id_periodo DESC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getCargosElecciones($idescuela = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM CargosEleccionesApp WHERE status_cargo=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getTerminalesElecciones($idescuela = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM TerminalesEleccionesApp WHERE status_terminal=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getCandidatosElecciones($idescuela = null, $ideleccion = null, $idcargo = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM CandidatosEleccionesApp WHERE status_candidato=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($ideleccion !== null) {
            $arraywhere['p_id_eleccion'] = $ideleccion;
            $sql = $sql . " AND id_eleccion=:p_id_eleccion ";
        }
        if ($idcargo !== null) {
            $arraywhere['p_id_cargo'] = $idcargo;
            $sql = $sql . " AND id_cargo=:p_id_cargo ";
        }

        $sql = $sql . " ORDER BY id_eleccion DESC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getVotosElecciones($idescuela = null, $ideleccion = null, $idcandidato = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM VotosEleccionesApp WHERE status_voto=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($ideleccion !== null) {
            $arraywhere['p_id_eleccion'] = $ideleccion;
            $sql = $sql . " AND id_eleccion=:p_id_eleccion ";
        }
        if ($idcandidato !== null) {
            $arraywhere['p_id_candidato'] = $idcandidato;
            $sql = $sql . " AND id_candidato=:p_id_candidato ";
        }

        $sql = $sql . " ORDER BY id_eleccion, id_candidato DESC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getConteoVotosElecciones($idescuela = null, $ideleccion = null, $idcargo = null, $idcandidato = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT COUNT(V.id_voto) conteo, C.id_escuela, C.id_cargo, V.id_candidato, C.nombrecompleto_candidato, C.numerotarjeton_candidato "
                . " FROM VotosEleccionesApp V INNER JOIN CandidatosEleccionesApp C ON V.id_candidato=C.id_candidato "
                . " WHERE status_voto=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND C.id_escuela=:p_id_escuela ";
        }
        if ($ideleccion !== null) {
            $arraywhere['p_id_eleccion'] = $ideleccion;
            $sql = $sql . " AND V.id_eleccion=:p_id_eleccion ";
        }
        if ($idcargo !== null) {
            $arraywhere['p_id_cargo'] = $idcargo;
            $sql = $sql . " AND C.id_cargo=:p_id_cargo ";
        }
        if ($idcandidato !== null) {
            $arraywhere['p_id_candidato'] = $idcandidato;
            $sql = $sql . " AND V.id_candidato=:p_id_candidato ";
        }

        $sql = $sql . " GROUP BY V.id_candidato ORDER BY V.id_eleccion, COUNT(V.id_candidato) DESC, V.id_candidato DESC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getLecturas($idescuela = null, $idarea = null, $idlectura = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM LecturasApp WHERE status_lectura=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($idarea !== null) {
            $arraywhere['p_id_area'] = $idarea;
            $sql = $sql . " AND id_area=:p_id_area ";
        }
        if ($idlectura !== null) {
            $arraywhere['p_id_lectura'] = $idlectura;
            $sql = $sql . " AND id_lectura=:p_id_lectura ";
        }
        $sql = $sql . " ORDER BY titulo_lectura ASC";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getCuestionarios($idescuela = null, $idcuestionario = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM CuestionariosApp WHERE status_cuestionario=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($idcuestionario !== null) {
            $arraywhere['p_id_cuestionario'] = $idcuestionario;
            $sql = $sql . " AND id_cuestionario=:p_id_cuestionario ";
        }
        $sql = $sql . " ORDER BY nombre_cuestionario ASC, activo_cuestionario DESC ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getPreguntas($idcuestionario = null, $idpregunta = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM PreguntasCuestionariosApp WHERE status_pregunta=1 ";
        $arraywhere = Array();
        if ($idcuestionario !== null) {
            $arraywhere['p_id_cuestionario'] = $idcuestionario;
            $sql = $sql . " AND id_cuestionario=:p_id_cuestionario ";
        }
        if ($idpregunta !== null) {
            $arraywhere['p_id_pregunta'] = $idpregunta;
            $sql = $sql . " AND id_pregunta=:p_id_pregunta ";
        }
        $sql = $sql . " ORDER BY nombrecorto_pregunta ASC, activo_pregunta DESC ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getOpcionesRespuesta($idpregunta = null, $idopcion = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM OpcionesRespuestaApp WHERE status_opcionrespuesta=1 ";
        $arraywhere = Array();
        if ($idpregunta !== null) {
            $arraywhere['p_id_pregunta'] = $idpregunta;
            $sql = $sql . " AND id_pregunta=:p_id_pregunta ";
        }
        if ($idopcion !== null) {
            $arraywhere['p_id_opcionrespuesta'] = $idopcion;
            $sql = $sql . " AND id_opcionrespuesta=:p_id_opcionrespuesta ";
        }
        $sql = $sql . " ORDER BY id_pregunta DESC ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getIntentosCuestionarios($idcuestionario = null, $idpersona = null, $idintento = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT I.*, P.nombre1_persona, P.apellido1_persona "
                . " FROM IntentosCuestionariosApp I "
                . " INNER JOIN PersonasApp P ON I.id_persona=P.id_persona"
                . " WHERE status_intento=1 ";
        $arraywhere = Array();
        if ($idcuestionario !== null) {
            $arraywhere['p_id_cuestionario'] = $idcuestionario;
            $sql = $sql . " AND I.id_cuestionario=:p_id_cuestionario ";
        }
        if ($idpersona !== null) {
            $arraywhere['p_id_persona'] = $idpersona;
            $sql = $sql . " AND I.id_persona=:p_id_persona ";
        }
        if ($idintento !== null) {
            $arraywhere['p_id_intento'] = $idintento;
            $sql = $sql . " AND I.id_intento=:p_id_intento ";
        }
        $sql = $sql . " ORDER BY I.id_intento DESC, I.id_persona";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getActividadesVirtuales($idescuela = null, $idprograma = null, $idasignatura = null, $iddocente = null, $numgrado = null, $idactividad = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT AV.*, Pr.nombre_programa, A.nombre_asignatura "
                . " FROM ActividadesVirtualesApp AV "
                . " INNER JOIN ProgramasApp Pr ON AV.id_programa=Pr.id_programa "
                . " INNER JOIN AsignaturasApp A ON AV.id_asignatura=A.id_asignatura "
                . " WHERE status_actividad=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND AV.id_escuela=:p_id_escuela ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND AV.id_programa=:p_id_programa ";
        }
        if ($idasignatura !== null) {
            $arraywhere['p_id_asignatura'] = $idasignatura;
            $sql = $sql . " AND AV.id_asignatura=:p_id_asignatura ";
        }
        if ($iddocente !== null) {
            $arraywhere['p_id_docente'] = $iddocente;
            $sql = $sql . " AND AV.id_docente=:p_id_docente ";
        }
        if ($numgrado !== null) {
            $arraywhere['p_num_grado'] = $numgrado;
            $sql = $sql . " AND AV.numgrado_programa=:p_num_grado ";
        }
        if ($idactividad !== null) {
            $arraywhere['p_id_actividad'] = $idactividad;
            $sql = $sql . " AND AV.id_actividad=:p_id_actividad ";
        }
        $sql = $sql . " ORDER BY id_escuela, id_programa, id_actividad DESC ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getSolucionesActividadesVirtuales($idactividad = null, $idestudiante = null, $idsolucion = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT OE.nombrecompleto_estudiante, SA.*, AV.* "
                . " FROM SolucionesActividadesVirtualesApp SA "
                . " INNER JOIN ActividadesVirtualesApp AV ON SA.id_actividad=AV.id_actividad "
                . " INNER JOIN ObservadorEstudianteApp OE ON SA.id_estudiante=OE.id_estudiante "
                . " WHERE SA.status_solucion=1";
        $arraywhere = Array();
        if ($idactividad !== null) {
            $arraywhere['p_id_actividad'] = $idactividad;
            $sql = $sql . " AND SA.id_actividad=:p_id_actividad ";
        }
        if ($idestudiante !== null) {
            $arraywhere['p_id_estudiante'] = $idestudiante;
            $sql = $sql . " AND SA.id_estudiante=:p_id_estudiante ";
        }
        if ($idsolucion !== null) {
            $arraywhere['p_id_solucion'] = $idsolucion;
            $sql = $sql . " AND SA.id_solucion=:p_id_solucion ";
        }
        $sql = $sql . " ORDER BY SA.id_actividad, SA.id_estudiante, SA.id_solucion DESC ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getEventos($idescuela = null, $idprograma = null, $visible = null, $idevento = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM EventosApp WHERE status_evento=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND id_escuela=:p_id_escuela ";
        }
        if ($idprograma !== null) {
            $arraywhere['p_id_programa'] = $idprograma;
            $sql = $sql . " AND id_programa=:p_id_programa ";
        }
        if ($visible !== null) {
            $arraywhere['p_visible'] = $visible;
            $sql = $sql . " AND visible_evento=:p_visible ";
        }
        if ($idevento !== null) {
            $arraywhere['p_id_evento'] = $idevento;
            $sql = $sql . " AND id_evento=:p_id_evento ";
        }
        $sql = $sql . " ORDER BY fechainicio_evento DESC ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

    public function getOVAS($idescuela = null, $idasignatura = null, $idautor = null, $numgrado = null) {
        $sql = null;
        $result = null;
        $sql = "SELECT O.*, A.nombre_asignatura, P.nombre1_persona, P.apellido1_persona FROM OVASApp O "
                . "INNER JOIN AsignaturasApp A ON O.id_asignatura=A.id_asignatura "
                . "INNER JOIN PersonasApp P ON O.id_autor=P.id_persona WHERE "
                . "O.status_ova=1 ";
        $arraywhere = Array();
        if ($idescuela !== null) {
            $arraywhere['p_id_escuela'] = $idescuela;
            $sql = $sql . " AND O.id_escuela=:p_id_escuela ";
        }
        if ($idasignatura !== null) {
            $arraywhere['p_id_asignatura'] = $idasignatura;
            $sql = $sql . " AND O.id_asignatura=:p_id_asignatura ";
        }
        if ($idautor !== null) {
            $arraywhere['p_id_autor'] = $idautor;
            $sql = $sql . " AND O.id_autor=:p_id_autor ";
        }
        if ($numgrado !== null) {
            $arraywhere['p_numgrado_ova'] = $numgrado;
            $sql = $sql . " AND O.numgrado_ova=:p_numgrado_ova ";
        }
        $sql = $sql . " ORDER BY O.id_ova DESC, O.id_asignatura ";
        $result = $this->selectJSONArray($sql, $arraywhere);
        return $result;
    }

}
