<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportesPDF
 *
 * @author LISANDRO
 */
class PDFReports extends TCPDF {

    public $bc = null;
    public $session = null;
    public $reportname = 'Reporte';
    public $pagesize = null;
    public $pageorientation = null;
    public $margintop = 0;
    public $marginbottom = 0;
    public $marginright = 0;
    public $marginleft = 0;
    public $fontfamilycontent = null;
    public $fontsizecontent = null;
    public $fontstylecontent = null;
    public $fontfamilyheader = null;
    public $fontsizeheader = null;
    public $fontstyleheader = null;
    public $fontfamilyfooter = null;
    public $fontsizefooter = null;
    public $fontstylefooter = null;
    public $headertype = 0;
    public $footertype = 0;

    public function __construct($urlsettingsdb = null) {
        parent::__construct();
        $this->bc = new ReportsBank($urlsettingsdb);
        $this->session = new SessionManager();
    }

    public function setMetaData() {
        $this->SetAuthor('JOSE MEZA');
        $this->SetCreator('EscuelaT!.');
        $this->SetSubject('PDF Report');
        $this->SetKeywords('Reporte, PDF, EscuelaT');
    }

    public function setReportName($name) {
        $this->reportname = $name;
        $this->SetTitle($name);
    }

    public function setFontHeader($family = '', $style = '', $size = 8) {
        $this->fontfamilyheader = $family;
        $this->fontstyleheader = $style;
        $this->fontsizeheader = $size;
    }

    public function setFontContent($family = '', $style = '', $size = 10) {
        $this->fontfamilycontent = $family;
        $this->fontstylecontent = $style;
        $this->fontsizecontent = $size;
    }

    public function setFontFooter($family = '', $style = '', $size = 6) {
        $this->fontfamilyfooter = $family;
        $this->fontstylefooter = $style;
        $this->fontsizefooter = $size;
    }

    public function setFormatPage($format, $orientation) {
        $this->formatpage = $format;
        $this->orientationpage = $orientation;
    }

    private function getSpanishActualDate() {
        $months = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $year = date("Y");
        $month = date("n");
        $day = date("j");
        $date = $day . " de " . $months[$month] . " de " . $year;
        return $date;
    }

    private function getSpanishOrdinalsNumbers($number) {
        $ordinals = array("Transicion", "Primero", "Segundo", "Tercero", "Cuarto", "Quinto", "Sexto", "Septimo", "Octavo", "Noveno", "Decimo", "Undecimo", "Duodecimo");
        if (isset($ordinals[$number])) {
            return $ordinals[$number];
        }
        return $number;
    }

    public function getRealPageWidth() {
        return ($this->getPageWidth() - $this->lMargin - $this->rMargin);
    }

    public function getRealPageHeight() {
        return ($this->getPageHeight() - $this->tMargin - $this->bMargin);
    }

    public function writeDefaultHeader() {
        if ($this->session->hasLogin()) {
            $configuracion = null;
            $configuracion = $this->bc->getConfiguracionEscuela($this->session->getEnterpriseID());
            if ($configuracion !== null && $configuracion !== '' && $configuracion !== '[]') {
                $configuracion = json_decode($configuracion, true);
            }

            if (is_array($configuracion)) {
                $this->Ln(1);
                $this->SetFont($this->fontfamilyheader, 'B', ($this->fontsizeheader + 4));
                $this->Cell(0, 8, $configuracion[0]['nombremostrar_configuracion'], 0, 1, 'C');
                $this->SetFont($this->fontfamilyheader, '', $this->fontsizeheader);
                $this->MultiCell(0, 6, $configuracion[0]['membrete_configuracion'], 0, 'C');
                $this->SetFont($this->fontfamilyheader, 'I', $this->fontsizeheader);
                $this->MultiCell(0, 6, $configuracion[0]['eslogan_configuracion'], 0, 'C');
                $this->Image('../../ImageFiles/' . $configuracion[0]['logo_configuracion'], 3, 3, 20, 20);
                
            } else {
                $this->Cell(0, 8, $this->session->getEnterpriseName(), 0, 0, 'C');
            }
            $this->Ln();
        }
    }

    public function writeDefaultFooter() {
        if ($this->session->hasLogin()) {
            $this->SetY(-15);
            $this->SetFont($this->fontfamilyfooter, '', 4);
            $this->Cell(0, 2, 'Sistema Integral de Gestion Academica', 0, 0, 'C');
            $this->Ln();
            $this->SetFont($this->fontfamilyfooter, 'B', 6);
            $this->Cell(0, 2, 'EscuelaT!', 0, 0, 'C');
        }
    }

    function Header() {
        if ($this->headertype === 0) {
            $this->writeDefaultHeader();
        }
    }

    function Footer() {
        if ($this->footertype === 0) {
            $this->writeDefaultFooter();
        }
    }

    public function ListadoEscuelas() {
        $result = null;
        $result = $this->bc->getEscuelas();
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            if (is_array($result) && count($result) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE ESCUELAS');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell(20, 8, 'CODIGO');
                $this->Cell(80, 8, 'NOMBRE DE ESCUELA');
                $this->Cell(80, 8, 'DIRECCION');
                $this->Cell(20, 8, 'TELEFONO');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($result); $i++) {
                    $this->Cell(20, 6, $result[$i]['id_escuela']);
                    $this->Cell(80, 6, $result[$i]['nombre_escuela']);
                    $this->Cell(80, 6, $result[$i]['direccion_escuela']);
                    $this->Cell(20, 6, $result[$i]['telefono_escuela']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoSedes($idescuela = null) {
        $result = null;
        $result = $this->bc->getSedes($idescuela);
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            if (is_array($result) && count($result) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE SEDES');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell(20, 8, 'CODIGO');
                $this->Cell(80, 8, 'NOMBRE DE SEDE');
                $this->Cell(80, 8, 'DIRECCION');
                $this->Cell(20, 8, 'TELEFONO');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($result); $i++) {
                    $this->Cell(20, 6, $result[$i]['id_sede']);
                    $this->Cell(80, 6, $result[$i]['nombre_sede']);
                    $this->Cell(80, 6, $result[$i]['direccion_sede']);
                    $this->Cell(20, 6, $result[$i]['telefono_sede']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoProgramas($idescuela = null) {
        $result = null;
        $result = $this->bc->getProgramas($idescuela);
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            if (is_array($result) && count($result) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE PROGRAMAS');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell(20, 8, 'CODIGO');
                $this->Cell(80, 8, 'NOMBRE DE PROGRAMA');
                $this->Cell(40, 8, 'NIVEL');
                $this->Cell(20, 8, 'GRADOS');
                $this->Cell(40, 8, 'PERIODICIDAD');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($result); $i++) {
                    $this->Cell(20, 6, $result[$i]['id_programa']);
                    $this->Cell(80, 6, $result[$i]['nombre_programa']);
                    $this->Cell(40, 6, $result[$i]['nivel_programa']);
                    $this->Cell(20, 6, $result[$i]['ngrados_programa']);
                    $this->Cell(40, 6, $result[$i]['periodicidadgrado_programa']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoAreas($idescuela = null) {
        $result = null;
        $result = $this->bc->getAreas($idescuela);
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            if (is_array($result) && count($result) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE AREAS');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell(20, 8, 'CODIGO');
                $this->Cell(180, 8, 'NOMBRE DE AREA');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($result); $i++) {
                    $this->Cell(20, 6, $result[$i]['id_area']);
                    $this->Cell(180, 6, $result[$i]['nombre_area']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoAsignaturas($idescuela = null) {
        $result = null;
        $result = $this->bc->getAsignaturas($idescuela);
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            if (is_array($result) && count($result) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE ASIGNATURAS');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell(20, 8, 'CODIGO');
                $this->Cell(140, 8, 'NOMBRE DE ASIGNATURA');
                $this->Cell(40, 8, 'AREA');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($result); $i++) {
                    $this->Cell(20, 6, $result[$i]['id_asignatura']);
                    $this->Cell(140, 6, $result[$i]['nombre_asignatura']);
                    $this->Cell(40, 6, $result[$i]['id_area']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoLogrosAsignaturas($idescuela = null, $idasignatura = null, $grado = null, $tipo = null) {
        $result = null;
        $result = $this->bc->getLogrosAsignaturas($idescuela, $idasignatura, $grado, $tipo);
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            if (is_array($result) && count($result) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE LOGROS Y/O COMPETENCIAS');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell(20, 4, 'CODIGO', 1);
                $this->Cell(80, 4, 'NOMBRE DE ASIGNATURA', 1);
                $this->Cell(20, 4, 'TIPO', 1);
                $this->Cell(20, 4, 'MINIMO', 1);
                $this->Cell(20, 4, 'MAXIMO', 1);
                $this->Cell(20, 4, 'GRADO', 1);
                $this->Cell(20, 4, 'PERIODO', 1);
                $this->Ln();
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($result); $i++) {
                    $this->SetFont($this->fontfamilycontent, 'B', $this->fontsizecontent);
                    $this->Cell(20, 4, $result[$i]['id_logro'], 1);
                    $this->Cell(80, 4, $result[$i]['nombre_asignatura'], 1);
                    $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                    $this->Cell(20, 4, $result[$i]['tipo_logro'], 1);
                    $this->Cell(20, 4, $result[$i]['min_logro'], 1);
                    $this->Cell(20, 4, $result[$i]['max_logro'], 1);
                    $this->Cell(20, 4, $result[$i]['numgrado_logro'], 1);
                    $this->Cell(20, 4, $result[$i]['numcorte_logro'], 1);
                    $this->Ln();
                    $this->MultiCell(200, 4, $result[$i]['descripcion_logro'], 1, 'L');
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoGrupos($idescuela = null, $idprograma = null, $numgrado = null) {
        $result = null;
        $result = $this->bc->getGrupos($idescuela, $idprograma, $numgrado);
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            if (is_array($result) && count($result) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE GRUPOS');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell(25, 8, 'CODIGO');
                $this->Cell(100, 8, 'PROGRAMA');
                $this->Cell(25, 8, 'SEDE');
                $this->Cell(25, 8, 'GRADO');
                $this->Cell(25, 8, 'GRUPO');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($result); $i++) {
                    $this->Cell(25, 6, $result[$i]['id_grupo']);
                    $this->Cell(100, 6, $result[$i]['nombre_programa']);
                    $this->Cell(25, 6, $result[$i]['id_sede']);
                    $this->Cell(25, 6, $result[$i]['numgrado_programa'] . '°');
                    $this->Cell(25, 6, $result[$i]['numgrado_programa'] . $result[$i]['num_grupo']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoDocentes() {
        $result = null;
        $result = $this->bc->getDocentes();
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            if (is_array($result) && count($result) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE DOCENTES');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell(20, 8, 'CODIGO');
                $this->Cell(100, 8, 'NOMBRE COMPLETO DOCENTE');
                $this->Cell(80, 8, 'TITULO ACADEMICO');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($result); $i++) {
                    $this->Cell(20, 6, $result[$i]['id_docente']);
                    $this->Cell(100, 6, $result[$i]['nombrecompleto_docente']);
                    $this->Cell(80, 6, $result[$i]['ultimotitulo_docente']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoCargasDocentes($idescuela = null, $idperiodo = null, $idprograma = null, $iddocente = null, $idgrupo = null) {
        $result = null;
        $result = $this->bc->getCargasDocentes($idescuela, $idperiodo, $idprograma, $iddocente, $idgrupo);
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            if (is_array($result) && count($result) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'CARGA ACADEMICA DOCENTE');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell(80, 8, 'NOMBRE COMPLETO DOCENTE');
                $this->Cell(40, 8, 'PROGRAMA');
                $this->Cell(40, 8, 'ASIGNATURA');
                $this->Cell(20, 8, 'GRUPO');
                $this->Cell(20, 8, 'AÑO');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($result); $i++) {
                    $this->Cell(80, 6, $result[$i]['nombrecompleto_docente']);
                    $this->Cell(40, 6, $result[$i]['nombre_programa']);
                    $this->Cell(40, 6, $result[$i]['nombre_asignatura']);
                    $this->Cell(20, 6, $result[$i]['id_grupo']);
                    $this->Cell(20, 6, $result[$i]['id_periodo']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoDirectoresGrupos($idescuela = null, $idgrupo = null, $idperiodo = null) {
        $result = null;
        $result = $this->bc->getDirectoresGrupos($idescuela, $idgrupo, $idperiodo);
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            if (is_array($result) && count($result) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE DIRECTORES DE GRUPOS');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell(20, 8, 'CODIGO');
                $this->Cell(100, 8, 'NOMBRE COMPLETO DOCENTE');
                $this->Cell(40, 8, 'GRUPO');
                $this->Cell(40, 8, 'PERIODO');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($result); $i++) {
                    $this->Cell(20, 6, $result[$i]['id_docente']);
                    $this->Cell(100, 6, $result[$i]['nombrecompleto_docente']);
                    $this->Cell(40, 6, $result[$i]['id_grupo']);
                    $this->Cell(40, 6, $result[$i]['id_periodo']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoEstudiantes($idescuela = null, $idsede = null, $idjornada = null, $idprograma = null, $numgrado = null, $idgrupo = null, $idperiodo = null) {
        $result = null;
        $result = $this->bc->getEstudiantesMatriculas($idescuela, $idsede, $idjornada, $idprograma, null, $numgrado, $idgrupo, $idperiodo);
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            if (is_array($result) && count($result) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE ESTUDIANTES');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell(20, 8, 'CODIGO');
                $this->Cell(80, 8, 'NOMBRE COMPLETO ESTUDIANTE');
                $this->Cell(20, 8, 'AÑO');
                $this->Cell(40, 8, 'PROGRAMA');
                $this->Cell(20, 8, 'GRADO');
                $this->Cell(20, 8, 'GRUPO');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($result); $i++) {
                    $this->Cell(20, 6, $result[$i]['id_estudiante']);
                    $this->Cell(80, 6, $result[$i]['nombrecompleto_estudiante']);
                    $this->Cell(20, 6, $result[$i]['anualidad_periodo']);
                    $this->Cell(40, 6, $result[$i]['nombre_programa']);
                    $this->Cell(20, 6, $result[$i]['numgrado_programa'] . '°');
                    $this->Cell(20, 6, $result[$i]['nombre_grupo']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoUsuarios($idescuela = null, $idtipousuario = null) {
        $result = null;
        $result = $this->bc->getUsuarios($idescuela, $idtipousuario);
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            if (is_array($result) && count($result) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE USUARIOS');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell(80, 8, 'NOMBRE COMPLETO');
                $this->Cell(40, 8, 'USUARIO');
                $this->Cell(30, 8, 'TIPO');
                $this->Cell(30, 8, 'EDITADO');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($result); $i++) {
                    $this->Cell(80, 6, $result[$i]['nombrecompleto_persona']);
                    $this->Cell(40, 6, $result[$i]['username_usuario']);
                    $this->Cell(30, 6, $result[$i]['id_tipousuario']);
                    $this->Cell(30, 6, $result[$i]['fechaedita_usuario']);
                    $this->Ln();
                }
            }
        }
    }

    public function CertificadoEstudios($idsede, $idjornada, $idprograma, $grado, $idgrupo, $idperiodo, $idestudiante, $idmatricula) {
        $result = null;
        $configuracion = null;
        $result = $this->bc->getEstudiantesMatriculas($this->session->getEnterpriseID(), $idsede, $idjornada, $idprograma, null, $grado, $idgrupo, $idperiodo, $idestudiante);
        $configuracion = $this->bc->getConfiguracionEscuela($this->session->getEnterpriseID());
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            $configuracion = json_decode($configuracion, true);
            $result = $result[0];
            $configuracion = $configuracion[0];
            if (is_array($result) && count($result) > 0) {
                $this->SetFont($this->fontfamilycontent, 'B', 16);
                $this->Cell(0, 6, 'CERTIFICADO DE NOTAS', 0, 1, 'C');
                $this->Ln(12);
                $this->SetFont($this->fontfamilycontent, 'B', 10);
                $this->Cell(0, 4, 'EL (LA) SUSCRITO(A) RECTOR(A)', 0, 1, 'C');
                $this->Cell(0, 4, 'DE', 0, 1, 'C');
                $this->Cell(0, 4, $configuracion['nombremostrar_configuracion'], 0, 1, 'C');
                $this->Ln(12);
                $this->SetFont($this->fontfamilycontent, 'B', 14);
                $this->Cell(0, 4, 'CERTIFICA', 0, 1, 'C');
                $this->SetFont($this->fontfamilycontent, 'B', 12);
                $this->Ln(8);
                $text1 = "Que " . strtoupper($result['nombrecompleto_estudiante']) . ""
                        . " identificado(a) con " . $result['tipodoc_persona'] . " número " . $result['documento_persona'] . ""
                        . " se encuentra matriculado(a) en esta Institución Educativa, "
                        . " y actualmente se encuentra cursando el grado " . strtoupper($this->getSpanishOrdinalsNumbers($result['numgrado_programa'])) . ""
                        . " de " . strtoupper($result['nombre_programa']) . ", en el grupo " . $result['nombre_grupo'] . ",  para el Año Lectivo " . $result['anualidad_periodo'] . ".";
                $text2 = "Se expide la presente certificación a petición de la parte interesada, y se firma el " . $this->getSpanishActualDate() . ".";
                $this->MultiCell(0, 6, $text1, 0, 'J', false, 1);
                $this->Ln(4);
                $this->MultiCell(0, 6, $text2, 0, 'L', false, 1);
                $this->Ln(18);
                $this->SetFont($this->fontfamilycontent, 'B', 12);
                $this->MultiCell(0, 6, '___________________________________', 0, 'C');
                $this->MultiCell(0, 6, $configuracion['nombrerector_configuracion'], 0, 'C');
                $this->MultiCell(0, 6, $configuracion['tipodocrector_configuracion'] . ": " . $configuracion['documentorector_configuracion'], 0, 'C');
                $this->SetFont($this->fontfamilycontent, 'B', 10);
                $this->MultiCell(0, 6, 'RECTOR(A)', 0, 'C');
            }
        }
    }

    public function CertificadoNotas($idsede, $idjornada, $idprograma, $grado, $idgrupo, $idperiodo, $idestudiante, $idmatricula) {
        $result = null;
        $configuracion = null;
        $result = $this->bc->getInformesCalificaciones($this->session->getEnterpriseID(), $idsede, $idjornada, $idprograma, null, $grado, $idgrupo, $idperiodo, $idestudiante);
        $configuracion = $this->bc->getConfiguracionEscuela($this->session->getEnterpriseID());
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            $configuracion = json_decode($configuracion, true);
            $result = $result[0];
            $configuracion = $configuracion[0];
            if (is_array($result) && count($result) > 0) {
                $this->SetFont($this->fontfamilycontent, 'B', 16);
                $this->Cell(0, 6, 'CERTIFICADO DE NOTAS', 0, 1, 'C');
                $this->Ln(6);
                $this->SetFont($this->fontfamilycontent, 'B', 10);
                $this->Cell(0, 4, 'EL (LA) SUSCRITO(A) RECTOR(A)', 0, 1, 'C');
                $this->Cell(0, 4, 'DE', 0, 1, 'C');
                $this->Cell(0, 4, $configuracion['nombremostrar_configuracion'], 0, 1, 'C');
                $this->Ln(6);
                $this->SetFont($this->fontfamilycontent, 'B', 14);
                $this->Cell(0, 4, 'CERTIFICA', 0, 1, 'C');
                $this->Ln(4);
                $this->SetFont($this->fontfamilycontent, '', 12);
                $pageWidth = $this->getRealPageWidth();
                $this->SetFont($this->fontfamilycontent, 'B', 12);
                $this->Cell($pageWidth / 2, 4, 'ESTUDIANTE', 0, 0, 'C');
                $this->Cell($pageWidth / 2, 4, 'IDENTIDAD', 0, 0, 'C');
                $this->Ln(4);
                $this->SetFont($this->fontfamilycontent, '', 12);
                $this->Cell($pageWidth / 2, 4, $result['nombrecompleto_estudiante'], 0, 0, 'C');
                $this->Cell($pageWidth / 2, 4, $result['tipodoc_persona'] . " número " . $result['documento_persona'], 0, 0, 'C');
                $this->Ln(8);
                $this->SetFont($this->fontfamilycontent, 'B', 12);
                $this->Cell($pageWidth / 3, 4, 'PROGRAMA', 0, 0, 'C');
                $this->Cell($pageWidth / 3, 4, 'SEDE', 0, 0, 'C');
                $this->Cell($pageWidth / 3, 4, 'JORNADA', 0, 0, 'C');
                $this->Ln(4);
                $this->SetFont($this->fontfamilycontent, '', 12);
                $this->Cell($pageWidth / 3, 4, $result['nombre_programa'], 0, 0, 'C');
                $this->Cell($pageWidth / 3, 4, $result['nombre_sede'], 0, 0, 'C');
                $this->Cell($pageWidth / 3, 4, $result['nombre_jornada'], 0, 0, 'C');
                $this->Ln(4);
                $this->SetFont($this->fontfamilycontent, 'B', 12);
                $this->Cell($pageWidth / 3, 4, 'AÑO', 0, 0, 'C');
                $this->Cell($pageWidth / 3, 4, 'GRADO', 0, 0, 'C');
                $this->Cell($pageWidth / 3, 4, 'GRUPO', 0, 0, 'C');
                $this->Ln(4);
                $this->SetFont($this->fontfamilycontent, '', 12);
                $this->Cell($pageWidth / 3, 4, $result['anualidad_periodo'], 0, 0, 'C');
                $this->Cell($pageWidth / 3, 4, $result['numgrado_programa'], 0, 0, 'C');
                $this->Cell($pageWidth / 3, 4, $result['nombre_grupo'], 0, 0, 'C');
                $this->Ln(8);

                $this->SetFont($this->fontfamilycontent, 'B', 12);
                $this->Cell($pageWidth * 42 / 100, 4, 'ASIGNATURA', 1, 0, 'L');
                $this->Cell($pageWidth * 7 / 100, 4, 'P1', 1, 0, 'C');
                $this->Cell($pageWidth * 7 / 100, 4, 'P2', 1, 0, 'C');
                $this->Cell($pageWidth * 7 / 100, 4, 'P3', 1, 0, 'C');
                $this->Cell($pageWidth * 7 / 100, 4, 'P4', 1, 0, 'C');
                $this->Cell($pageWidth * 7 / 100, 4, 'P5', 1, 0, 'C');
                $this->Cell($pageWidth * 7 / 100, 4, 'P6', 1, 0, 'C');
                $this->Cell($pageWidth * 16 / 100, 4, 'Definitiva', 1, 0, 'C');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', 12);
                $calificaciones = null;
                if (isset($result['calificaciones'])) {
                    $calificaciones = $result['calificaciones'];
                    if ($calificaciones !== null && $calificaciones !== '' && $calificaciones !== '[]') {
                        $calificaciones = json_decode($calificaciones, true);
                    }
                }
                if (is_array($calificaciones) && count($calificaciones) > 0) {
                    for ($j = 0; $j < count($calificaciones); $j++) {
                        $this->Cell($pageWidth * 42 / 100, 4, $calificaciones[$j]['nombre_asignatura'], 1, 0, 'L');
                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$j]['np1']), 1, 0, 'C');
                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$j]['np2']), 1, 0, 'C');
                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$j]['np3']), 1, 0, 'C');
                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$j]['np4']), 1, 0, 'C');
                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$j]['np5']), 1, 0, 'C');
                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$j]['np6']), 1, 0, 'C');
                        $this->Cell($pageWidth * 16 / 100, 4, $calificaciones[$j]['def'], 1, 0, 'C');
                        $this->Ln();
                    }
                }
                $this->Ln();
                $text2 = "Se expide la presente certificación a petición de la parte interesada, y se firma el " . $this->getSpanishActualDate() . ".";
                $this->MultiCell(0, 6, $text2, 0, 'L', false, 1);
                $this->Ln(12);
                $this->SetFont($this->fontfamilycontent, 'B', 12);
                $this->MultiCell(0, 6, '___________________________________', 0, 'C');
                $this->MultiCell(0, 6, $configuracion['nombrerector_configuracion'], 0, 'C');
                $this->MultiCell(0, 6, $configuracion['tipodocrector_configuracion'] . ": " . $configuracion['documentorector_configuracion'], 0, 'C');
                $this->SetFont($this->fontfamilycontent, 'B', 10);
                $this->MultiCell(0, 6, 'RECTOR(A)', 0, 'C');
            }
        }
    }

    public function InformeCalificaionesTipo1($idescuela = null, $idsede = null, $idjornada = null, $idprograma = null, $idplanestudio = null, $grado = null, $idgrupo = null, $idperiodo = null, $idcorte = null, $idestudiante = null, $idmatricula = null) {
        $htmltable = '';
        $data = null;
        $subdata = null;
        $data = $this->bc->getInformesCalificaciones($idescuela, $idsede, $idjornada, $idprograma, $idplanestudio, $grado, $idgrupo, $idperiodo, $idestudiante, $idmatricula);
        $corte = $this->bc->selectJSONArray("SELECT numero_corte FROM CortesPeriodosApp WHERE id_corte = '$idcorte'");
        if ($corte !== null && $corte !== '[]') {
            $corte = json_decode($corte, true);
            $corte = $corte[0]['numero_corte'];
        }
        if ($data !== null && $data !== '' && $data !== '[]') {
            $this->SetFont($this->fontfamilycontent, $this->fontstylecontent, $this->fontsizecontent);
            $data = json_decode($data, true);
            if ($data !== null && is_array($data)) {
                for ($i = 0; $i < count($data); $i++) {
                    $this->SetFont($this->fontfamilycontent, $this->fontstylecontent, $this->fontsizecontent);
                    $headtable = '';

                    $headtable = $headtable . '<table border="0">';
                    $headtable = $headtable . '<tr>';
                    $headtable = $headtable . '<th style="width: 40%;"><b>Estudiante:</b></th>';
                    $headtable = $headtable . '<th style="width: 20%;"><b>Programa:</b></th>';
                    $headtable = $headtable . '<th style="width: 20%;"><b>Sede:</b></th>';
                    $headtable = $headtable . '<th style="width: 20%;"><b>Jornada:</b></th>';
                    $headtable = $headtable . '</tr>';
                    $headtable = $headtable . '<tr>';
                    $headtable = $headtable . '<td>' . $data[$i]['nombrecompleto_estudiante'] . '</td>';
                    $headtable = $headtable . '<td>' . $data[$i]['nombre_programa'] . '</td>';
                    $headtable = $headtable . '<td>' . $data[$i]['nombre_sede'] . '</td>';
                    $headtable = $headtable . '<td>' . $data[$i]['nombre_jornada'] . '</td>';
                    $headtable = $headtable . '</tr>';
                    $headtable = $headtable . '</table>';

                    $headtable = $headtable . '<table border="0">';
                    $headtable = $headtable . '<tr>';
                    $headtable = $headtable . '<th style="width: 15%;"><b>Año:</b></th>';
                    $headtable = $headtable . '<th style="width: 15%;"><b>Periodo:</b></th>';
                    $headtable = $headtable . '<th style="width: 15%;"><b>Grado:</b></th>';
                    $headtable = $headtable . '<th style="width: 15%;"><b>Grupo:</b></th>';
                    $headtable = $headtable . '<th style="width: 40%;"><b>Director Grupo:</b></th>';
                    $headtable = $headtable . '</tr>';
                    $headtable = $headtable . '<tr>';
                    $headtable = $headtable . '<td>' . $data[$i]['anualidad_periodo'] . '</td>';
                    $headtable = $headtable . '<td>' . $corte . '° ' . '</td>';
                    $headtable = $headtable . '<td>' . $data[$i]['numgrado_programa'] . '°</td>';
                    $headtable = $headtable . '<td>' . $data[$i]['nombre_grupo'] . '</td>';
                    $headtable = $headtable . '<td>' . utf8_decode($data[$i]['nombre_director']) . '</td>';
                    $headtable = $headtable . '</tr>';
                    $headtable = $headtable . '</table>';
                    $this->writeHTML($headtable, true, false, false, false, '');

                    $htmltable = $htmltable . '<table border="1">';
                    $htmltable = $htmltable . '<tr>';
                    $htmltable = $htmltable . '<th style="text-align: justify; width: 30%;"><b>AREAS Y/O ASIGNATURA</b></th>';
                    $htmltable = $htmltable . '<th style="text-align: center; width: 4%;"><b>IH</b></th>';
                    $htmltable = $htmltable . '<th style="text-align: center; width: 4%;"><b>P1</b></th>';
                    $htmltable = $htmltable . '<th style="text-align: center; width: 4%;"><b>P2</b></th>';
                    $htmltable = $htmltable . '<th style="text-align: center; width: 4%;"><b>P3</b></th>';
                    $htmltable = $htmltable . '<th style="text-align: center; width: 4%;"><b>P4</b></th>';
                    $htmltable = $htmltable . '<th style="text-align: center; width: 5%;"><b>Acu</b></th>';
                    $htmltable = $htmltable . '<th style="text-align: center; width: 5%;"><b>Aus</b></th>';
                    $htmltable = $htmltable . '<th style="text-align: center; width: 40%;"><b>LOGROS / DIFICULTADES / RECOMENDACIONES </b></th>';
                    $htmltable = $htmltable . '</tr>';

                    $subdata = $data[$i]['calificaciones'];
                    if ($subdata !== null && $subdata !== '' && $subdata !== '[]') {
                        $subdata = json_decode($subdata, true);
                    }
                    if ($subdata !== null && is_array($subdata)) {
                        for ($j = 0; $j < count($subdata); $j++) {
                            $htmltable = $htmltable . '<tr>';
                            $htmltable = $htmltable . '<td><label style="font-size:4pt; padding-top: 0px;">' . $subdata[$j]['nombre_area'] . '</label> <br>'
                                    . '<b>' . $subdata[$j]['nombre_asignatura'] . '</b></td>';
                            $htmltable = $htmltable . '<td style="text-align: center;">' . ($subdata[$j]['hteoricas_asignatura'] + $subdata[$j]['hpracticas_asignatura']) . '</td>';
                            $htmltable = $htmltable . '<td style="text-align: center;">' . $subdata[$j]['np1'] . '</td>';
                            $htmltable = $htmltable . '<td style="text-align: center;">' . $subdata[$j]['np2'] . '</td>';
                            $htmltable = $htmltable . '<td style="text-align: center;">' . $subdata[$j]['np3'] . '</td>';
                            $htmltable = $htmltable . '<td style="text-align: center;">' . $subdata[$j]['np4'] . '</td>';
                            $htmltable = $htmltable . '<td style="text-align: center;">' . $subdata[$j]['def'] . '</td>';
                            $htmltable = $htmltable . '<td style="text-align: center;">';
                            if ($corte !== null) {
                                $htmltable = $htmltable . $subdata[$j]['p' . $corte . '_ausencias_calificacion'];
                            }
                            $htmltable = $htmltable . '</td>';
                            $htmltable = $htmltable . '<td style="text-align: justify;">';
                            if ($corte !== null && is_numeric($corte)) {
                                if ($subdata[$j]['p' . $corte . '_descripcion_logroc'] !== '') {
                                    $htmltable = $htmltable . ' - ' . utf8_decode($subdata[$j]['p' . $corte . '_descripcion_logroc']) . '';
                                }
                                if ($subdata[$j]['p' . $corte . '_descripcion_logrop'] !== '') {
                                    $htmltable = $htmltable . '<br> - ' . utf8_decode($subdata[$j]['p' . $corte . '_descripcion_logrop']) . '';
                                }
                                if ($subdata[$j]['p' . $corte . '_descripcion_logroa'] !== '') {
                                    $htmltable = $htmltable . '<br> - ' . utf8_decode($subdata[$j]['p' . $corte . '_descripcion_logroa']) . '';
                                }
                                if ($subdata[$j]['p' . $corte . '_comentarios_calificacion'] !== '') {
                                    $htmltable = $htmltable . '<br> - ' . utf8_decode($subdata[$j]['p' . $corte . '_comentarios_calificacion']) . '';
                                }
                            }
                            $htmltable = $htmltable . '</td>';

                            $htmltable = $htmltable . '</tr>';
                        }
                    }
                    $htmltable = $htmltable . '</table>';
                    $this->SetFontSize($this->fontsizecontent);
                    $this->writeHTML($htmltable, true, false, false, false, '');
                    $this->MultiCell(0, 30, 'Observaciones:', 1);
                    $htmltable = '';
                    if ($i < (count($data) - 1)) {
                        $this->AddPage();
                    }
                }
            }
        }
    }

    public function generatePDFDocument() {
        $ahora = getdate();
        $tiempo = $ahora['year'] . $ahora['mon'] . $ahora['mday'] . $ahora['hours'] . $ahora['minutes'] . $ahora['seconds'];
        $this->Output($this->reportname . ' ' . $tiempo . '.pdf', 'I');
    }

}
