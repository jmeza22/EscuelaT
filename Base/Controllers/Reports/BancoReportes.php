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
        $sql = "SELECT * FROM TiposUsuariosApp WHERE status_tipousuario=1 ORDER BY nombre_tipousuario ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getEscuelas() {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM EscuelasApp WHERE status_escuela=1 ORDER BY nombre_escuela ASC";
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
        $sql = "SELECT * FROM GruposApp WHERE status_grupo=1 ";
        if ($idescuela !== null) {
            $sql = $sql . " AND id_escuela='$idescuela' ";
        }
        if ($idprograma !== null) {
            $sql = $sql . " AND id_programa='$idprograma' ";
        }
        $sql = $sql . " ORDER BY id_grupo ASC";
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
        $sql = "SELECT * FROM PersonasApp WHERE status_persona=1 ORDER BY id_persona ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getDocentes() {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM DocentesApp WHERE status_docente=1 ORDER BY nombrecompleto_docente ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getEstudiantes() {
        $sql = null;
        $result = null;
        $sql = "SELECT * FROM ObservadorEstudianteApp WHERE status_estudiante=1 ORDER BY nombrecompleto_estudiante ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

    public function getEstudiantesMatriculas($idescuela = null, $idsede = null, $idprograma = null, $idplanestudio = null, $grado = null, $idgrupo = null, $idestudiante = null) {
        $sql = null;
        $result = null;
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
        if ($idgrupo !== null) {
            $sql = $sql . " AND OE.id_estudiante='$idestudiante' ";
        }
        $sql = $sql . " ORDER BY OE.nombrecompleto_estudiante ASC";
        $result = $this->selectJSONArray($sql);
        return $result;
    }

}
