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

    private function getSpanishGender($shortcode) {
        if ($shortcode !== null) {
            if ($shortcode === 'M') {
                return 'MASCULINO';
            }
            if ($shortcode === 'F') {
                return 'FEMENINO';
            }
        }
        return $shortcode;
    }

    private function getSpanishBoolean($shortcode) {
        if ($shortcode !== null) {
            if ($shortcode == 0) {
                return 'NO';
            }
            if ($shortcode == 1) {
                return 'SI';
            }
        }
        return $shortcode;
    }

    private function getLongNameColombianID($shortname) {
        $longnames = array();
        $longnames['RC'] = 'Registro Civil';
        $longnames['TI'] = 'Tarjeta de Identidad';
        $longnames['CC'] = 'Cedula de Ciudadanía';
        $longnames['CE'] = 'Cedula de Extranjería';
        $longnames['PP'] = 'Pasaporte';
        if (isset($longnames[$shortname])) {
            return $longnames[$shortname];
        }
        return $shortname;
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
                $this->Ln(4);
                $this->SetFont($this->fontfamilyheader, 'B', ($this->fontsizeheader + 4));
                $this->Cell(0, 8, $configuracion[0]['nombremostrar_configuracion'], 0, 1, 'C');
                $this->SetFont($this->fontfamilyheader, '', $this->fontsizeheader);
                $this->MultiCell(0, 6, $configuracion[0]['membrete_configuracion'], 0, 'C');
                $this->SetFont($this->fontfamilyheader, 'I', $this->fontsizeheader);
                $this->MultiCell(0, 6, $configuracion[0]['eslogan_configuracion'], 0, 'C');
                $this->setAlpha(0.05);
                $this->Image('../../ImageFiles/' . $configuracion[0]['logo_configuracion'], 0, 0, $this->getRealPageWidth(), $this->getRealPageHeight());
                $this->setAlpha(1);
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
        $data = null;
        $data = $this->bc->getEscuelas();
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
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
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell(20, 6, $data[$i]['id_escuela']);
                    $this->Cell(80, 6, $data[$i]['nombre_escuela']);
                    $this->Cell(80, 6, $data[$i]['direccion_escuela']);
                    $this->Cell(20, 6, $data[$i]['telefono_escuela']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoSedes($idescuela = null) {
        $data = null;
        $data = $this->bc->getSedes($idescuela);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
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
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell(20, 6, $data[$i]['id_sede']);
                    $this->Cell(80, 6, $data[$i]['nombre_sede']);
                    $this->Cell(80, 6, $data[$i]['direccion_sede']);
                    $this->Cell(20, 6, $data[$i]['telefono_sede']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoProgramas($idescuela = null) {
        $data = null;
        $data = $this->bc->getProgramas($idescuela);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
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
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell(20, 6, $data[$i]['id_programa']);
                    $this->Cell(80, 6, $data[$i]['nombre_programa']);
                    $this->Cell(40, 6, $data[$i]['nivel_programa']);
                    $this->Cell(20, 6, $data[$i]['ngrados_programa']);
                    $this->Cell(40, 6, $data[$i]['periodicidadgrado_programa']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoAreas($idescuela = null) {
        $data = null;
        $data = $this->bc->getAreas($idescuela);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE AREAS');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell(20, 8, 'CODIGO');
                $this->Cell(180, 8, 'NOMBRE DE AREA');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell(20, 6, $data[$i]['id_area']);
                    $this->Cell(180, 6, $data[$i]['nombre_area']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoAsignaturas($idescuela = null) {
        $data = null;
        $data = $this->bc->getAsignaturas($idescuela);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
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
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell(20, 6, $data[$i]['id_asignatura']);
                    $this->Cell(140, 6, $data[$i]['nombre_asignatura']);
                    $this->Cell(40, 6, $data[$i]['id_area']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoLogrosAsignaturas($idescuela = null, $idasignatura = null, $grado = null, $tipo = null) {
        $data = null;
        $data = $this->bc->getLogrosAsignaturas($idescuela, $idasignatura, $grado, $tipo);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
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
                for ($i = 0; $i < count($data); $i++) {
                    $this->SetFont($this->fontfamilycontent, 'B', $this->fontsizecontent);
                    $this->Cell(20, 4, $data[$i]['id_logro'], 1);
                    $this->Cell(80, 4, $data[$i]['nombre_asignatura'], 1);
                    $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                    $this->Cell(20, 4, $data[$i]['tipo_logro'], 1);
                    $this->Cell(20, 4, $data[$i]['min_logro'], 1);
                    $this->Cell(20, 4, $data[$i]['max_logro'], 1);
                    $this->Cell(20, 4, $data[$i]['numgrado_logro'], 1);
                    $this->Cell(20, 4, $data[$i]['numcorte_logro'], 1);
                    $this->Ln();
                    $this->MultiCell(200, 4, $data[$i]['descripcion_logro'], 1, 'L');
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoGrupos($idescuela = null, $idprograma = null, $numgrado = null) {
        $data = null;
        $data = $this->bc->getGrupos($idescuela, $idprograma, $numgrado);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
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
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell(25, 6, $data[$i]['id_grupo']);
                    $this->Cell(100, 6, $data[$i]['nombre_programa']);
                    $this->Cell(25, 6, $data[$i]['id_sede']);
                    $this->Cell(25, 6, $data[$i]['numgrado_programa'] . '°');
                    $this->Cell(25, 6, $data[$i]['numgrado_programa'] . $data[$i]['num_grupo']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoDocentes() {
        $data = null;
        $data = $this->bc->getDocentes();
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
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
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell(20, 6, $data[$i]['id_docente']);
                    $this->Cell(100, 6, $data[$i]['nombrecompleto_docente']);
                    $this->Cell(80, 6, $data[$i]['ultimotitulo_docente']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoCargasDocentes($idescuela = null, $idperiodo = null, $idprograma = null, $iddocente = null, $idgrupo = null) {
        $data = null;
        $data = $this->bc->getCargasDocentes($idescuela, $idperiodo, $idprograma, $iddocente, $idgrupo);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
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
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell(80, 6, $data[$i]['nombrecompleto_docente']);
                    $this->Cell(40, 6, $data[$i]['nombre_programa']);
                    $this->Cell(40, 6, $data[$i]['nombre_asignatura']);
                    $this->Cell(20, 6, $data[$i]['id_grupo']);
                    $this->Cell(20, 6, $data[$i]['id_periodo']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoDirectoresGrupos($idescuela = null, $idgrupo = null, $idperiodo = null) {
        $data = null;
        $data = $this->bc->getDirectoresGrupos($idescuela, $idgrupo, $idperiodo);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
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
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell(20, 6, $data[$i]['id_docente']);
                    $this->Cell(100, 6, $data[$i]['nombrecompleto_docente']);
                    $this->Cell(40, 6, $data[$i]['id_grupo']);
                    $this->Cell(40, 6, $data[$i]['id_periodo']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoEstudiantes($idescuela = null, $idsede = null, $idjornada = null, $idprograma = null, $numgrado = null, $idgrupo = null, $idperiodo = null) {
        $data = null;
        $data = $this->bc->getEstudiantesMatriculas($idescuela, $idsede, $idjornada, $idprograma, null, $numgrado, $idgrupo, $idperiodo);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
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
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell(20, 6, $data[$i]['id_estudiante']);
                    $this->Cell(80, 6, $data[$i]['nombrecompleto_estudiante']);
                    $this->Cell(20, 6, $data[$i]['anualidad_periodo']);
                    $this->Cell(40, 6, $data[$i]['nombre_programa']);
                    $this->Cell(20, 6, $data[$i]['numgrado_programa'] . '°');
                    $this->Cell(20, 6, $data[$i]['nombre_grupo']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoUsuarios($idescuela = null, $idtipousuario = null) {
        $data = null;
        $data = $this->bc->getUsuarios($idescuela, $idtipousuario);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
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
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell(80, 6, strtoupper($data[$i]['nombrecompleto_persona']));
                    $this->Cell(40, 6, strtoupper($data[$i]['username_usuario']));
                    $this->Cell(30, 6, $data[$i]['id_tipousuario']);
                    $this->Cell(30, 6, $data[$i]['fechaedita_usuario']);
                    $this->Ln();
                }
            }
        }
    }

    public function CertificadoEstudios($idsede, $idjornada, $idprograma, $grado, $idgrupo, $idperiodo, $idestudiante, $idmatricula) {
        $data = null;
        $configuracion = null;
        $data = $this->bc->getEstudiantesMatriculas($this->session->getEnterpriseID(), $idsede, $idjornada, $idprograma, null, $grado, $idgrupo, $idperiodo, $idestudiante);
        $configuracion = $this->bc->getConfiguracionEscuela($this->session->getEnterpriseID());
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            $configuracion = json_decode($configuracion, true);
            $data = $data[0];
            $configuracion = $configuracion[0];
            if (is_array($data) && count($data) > 0) {
                $this->Ln(16);
                $this->SetFont($this->fontfamilycontent, 'B', 16);
                $this->Cell(0, 6, 'CERTIFICADO DE ESTUDIOS', 0, 1, 'C');
                $this->Ln(12);
                $this->SetFont($this->fontfamilycontent, 'B', 10);
                $this->Cell(0, 4, 'EL (LA) SUSCRITO(A) RECTOR(A)', 0, 1, 'C');
                $this->Cell(0, 4, 'DE', 0, 1, 'C');
                $this->SetFont($this->fontfamilycontent, 'B', 12);
                $this->Cell(0, 4, strtoupper($configuracion['nombremostrar_configuracion']), 0, 1, 'C');
                $this->Ln(12);
                $this->SetFont($this->fontfamilycontent, 'B', 14);
                $this->Cell(0, 4, 'CERTIFICA', 0, 1, 'C');
                $this->SetFont($this->fontfamilycontent, 'B', 12);
                $this->Ln(8);
                $text1 = "Que " . strtoupper($data['nombrecompleto_estudiante']) . ""
                        . " identificado(a) con " . $this->getLongNameColombianID($data['tipodoc_persona']) . " número " . $data['documento_persona'] . ""
                        . " se encuentra matriculado(a) en esta Institución Educativa, "
                        . " y actualmente se encuentra cursando el grado " . strtoupper($this->getSpanishOrdinalsNumbers($data['numgrado_programa'])) . ""
                        . " de " . strtoupper($data['nombre_programa']) . ", en el grupo " . $data['nombre_grupo'] . ",  para el Año Lectivo " . $data['anualidad_periodo'] . ".";
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
        $data = null;
        $configuracion = null;
        $data = $this->bc->getInformesCalificaciones($this->session->getEnterpriseID(), $idsede, $idjornada, $idprograma, null, $grado, $idgrupo, $idperiodo, $idestudiante);
        $configuracion = $this->bc->getConfiguracionEscuela($this->session->getEnterpriseID());
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            $configuracion = json_decode($configuracion, true);
            $data = $data[0];
            $configuracion = $configuracion[0];
            if (is_array($data) && count($data) > 0) {
                $this->Ln(4);
                $this->SetFont($this->fontfamilycontent, 'B', 16);
                $this->Cell(0, 6, 'CERTIFICADO DE NOTAS', 0, 1, 'C');
                $this->Ln(8);
                $this->SetFont($this->fontfamilycontent, 'B', 10);
                $this->Cell(0, 4, 'EL (LA) SUSCRITO(A) RECTOR(A)', 0, 1, 'C');
                $this->Cell(0, 4, 'DE', 0, 1, 'C');
                $this->SetFont($this->fontfamilycontent, 'B', 12);
                $this->Cell(0, 4, $configuracion['nombremostrar_configuracion'], 0, 1, 'C');
                $this->Ln(6);
                $this->SetFont($this->fontfamilycontent, 'B', 14);
                $this->Cell(0, 4, 'CERTIFICA', 0, 1, 'C');
                $this->Ln(4);
                $this->SetFont($this->fontfamilycontent, '', 12);
                $pageWidth = $this->getRealPageWidth();
                $this->SetFont($this->fontfamilycontent, 'B', 12);
                $this->Cell($pageWidth * 50 / 100, 4, 'NOMBRE DE ESTUDIANTE', 0, 0, 'C');
                $this->Cell($pageWidth * 50 / 100, 4, 'DOCUMENTO DE IDENTIDAD', 0, 0, 'C');
                $this->Ln(4);
                $this->SetFont($this->fontfamilycontent, '', 12);
                $this->Cell($pageWidth * 50 / 100, 4, strtoupper($data['nombrecompleto_estudiante']), 0, 0, 'C');
                $this->Cell($pageWidth * 50 / 100, 4, strtoupper($this->getLongNameColombianID($data['tipodoc_persona'])) . " No. " . $data['documento_persona'], 0, 0, 'C');
                $this->Ln(6);
                $this->SetFont($this->fontfamilycontent, 'B', 12);
                $this->Cell($pageWidth * 33.333 / 100, 4, 'PROGRAMA', 0, 0, 'C');
                $this->Cell($pageWidth * 33.333 / 100, 4, 'SEDE', 0, 0, 'C');
                $this->Cell($pageWidth * 33.333 / 100, 4, 'JORNADA', 0, 0, 'C');
                $this->Ln(4);
                $this->SetFont($this->fontfamilycontent, '', 12);
                $this->Cell($pageWidth * 33.333 / 100, 4, strtoupper($data['nombre_programa']), 0, 0, 'C');
                $this->Cell($pageWidth * 33.333 / 100, 4, strtoupper($data['nombre_sede']), 0, 0, 'C');
                $this->Cell($pageWidth * 33.333 / 100, 4, strtoupper($data['nombre_jornada']), 0, 0, 'C');
                $this->Ln(6);
                $this->SetFont($this->fontfamilycontent, 'B', 12);
                $this->Cell($pageWidth * 33.333 / 100, 4, 'AÑO', 0, 0, 'C');
                $this->Cell($pageWidth * 33.333 / 100, 4, 'GRADO', 0, 0, 'C');
                $this->Cell($pageWidth * 33.333 / 100, 4, 'GRUPO', 0, 0, 'C');
                $this->Ln(4);
                $this->SetFont($this->fontfamilycontent, '', 12);
                $this->Cell($pageWidth * 33.333 / 100, 4, strtoupper($data['anualidad_periodo']), 0, 0, 'C');
                $this->Cell($pageWidth * 33.333 / 100, 4, strtoupper($data['numgrado_programa']), 0, 0, 'C');
                $this->Cell($pageWidth * 33.333 / 100, 4, strtoupper($data['nombre_grupo']), 0, 0, 'C');
                $this->Ln(6);

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
                if (isset($data['calificaciones'])) {
                    $calificaciones = $data['calificaciones'];
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
                $this->MultiCell(0, 6, strtoupper($configuracion['nombrerector_configuracion']), 0, 'C');
                $this->MultiCell(0, 6, $configuracion['tipodocrector_configuracion'] . ": " . $configuracion['documentorector_configuracion'], 0, 'C');
                $this->SetFont($this->fontfamilycontent, 'B', 10);
                $this->MultiCell(0, 6, 'RECTOR(A)', 0, 'C');
            }
        }
    }

    public function InformeCalificacionesSimple($idsede, $idjornada, $idprograma, $grado, $idgrupo, $idperiodo, $idcorte, $idestudiante) {
        $data = null;
        $configuracion = null;
        $data = $this->bc->getInformesCalificaciones($this->session->getEnterpriseID(), $idsede, $idjornada, $idprograma, null, $grado, $idgrupo, $idperiodo, $idestudiante);
        $configuracion = $this->bc->getConfiguracionEscuela($this->session->getEnterpriseID());
        $corte = $this->bc->getCortesPeriodos($this->session->getEnterpriseID(), $idperiodo, $idcorte);
        if ($corte !== null && $corte !== '[]') {
            $corte = json_decode($corte, true);
            $corte = $corte[0]['numero_corte'];
        }
        $pageWidth = $this->getRealPageWidth();
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            $configuracion = json_decode($configuracion, true);
            $configuracion = $configuracion[0];
            if (is_array($data) && count($data) > 0) {
                for ($i = 0; $i < count($data); $i++) {
                    $calificaciones = null;
                    if (isset($data[$i]['calificaciones'])) {
                        $calificaciones = $data[$i]['calificaciones'];
                        if ($calificaciones !== null && $calificaciones !== '' && $calificaciones !== '[]') {
                            $calificaciones = json_decode($calificaciones, true);
                        }
                    }
                    if ($calificaciones !== null && is_array($calificaciones)) {
                        $this->SetFont($this->fontfamilycontent, 'B', 16);
                        $this->Cell(0, 6, 'INFORME DE NOTAS', 0, 1, 'C');
                        $this->Ln(2);
                        $this->SetFont($this->fontfamilycontent, '', 12);
                        $this->Cell($pageWidth, 4, 'ESTUDIANTE', 0, 0, 'C');
                        $this->Ln(4);
                        $this->SetFont($this->fontfamilycontent, 'B', 14);
                        $this->Cell($pageWidth, 4, strtoupper($data[$i]['nombrecompleto_estudiante']), 0, 0, 'C');
                        $this->Ln(6);
                        $this->SetFont($this->fontfamilycontent, 'B', 12);
                        $this->Cell($pageWidth * 33.333 / 100, 4, 'PROGRAMA', 0, 0, 'C');
                        $this->Cell($pageWidth * 33.333 / 100, 4, 'SEDE', 0, 0, 'C');
                        $this->Cell($pageWidth * 33.333 / 100, 4, 'JORNADA', 0, 0, 'C');
                        $this->Ln(4);
                        $this->SetFont($this->fontfamilycontent, '', 10);
                        $this->Cell($pageWidth * 33.333 / 100, 4, strtoupper($data[$i]['nombre_programa']), 0, 0, 'C');
                        $this->Cell($pageWidth * 33.333 / 100, 4, strtoupper($data[$i]['nombre_sede']), 0, 0, 'C');
                        $this->Cell($pageWidth * 33.333 / 100, 4, strtoupper($data[$i]['nombre_jornada']), 0, 0, 'C');
                        $this->Ln(5);
                        $this->SetFont($this->fontfamilycontent, 'B', 12);
                        $this->Cell($pageWidth * 25 / 100, 4, 'AÑO', 0, 0, 'C');
                        $this->Cell($pageWidth * 25 / 100, 4, 'PERIODO', 0, 0, 'C');
                        $this->Cell($pageWidth * 25 / 100, 4, 'GRADO', 0, 0, 'C');
                        $this->Cell($pageWidth * 25 / 100, 4, 'GRUPO', 0, 0, 'C');
                        $this->Ln(4);
                        $this->SetFont($this->fontfamilycontent, '', 10);
                        $this->Cell($pageWidth * 25 / 100, 4, $data[$i]['anualidad_periodo'], 0, 0, 'C');
                        $this->Cell($pageWidth * 25 / 100, 4, 'P' . $corte, 0, 0, 'C');
                        $this->Cell($pageWidth * 25 / 100, 4, strtoupper($this->getSpanishOrdinalsNumbers($data[$i]['numgrado_programa'])), 0, 0, 'C');
                        $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['nombre_grupo']), 0, 0, 'C');
                        $this->Ln(8);

                        $this->SetFont($this->fontfamilycontent, 'B', 12);
                        $this->Cell($pageWidth * 42 / 100, 4, 'ASIGNATURA', 1, 0, 'L');
                        $this->Cell($pageWidth * 6 / 100, 4, 'IH', 1, 0, 'C');
                        $this->Cell($pageWidth * 6 / 100, 4, 'P1', 1, 0, 'C');
                        $this->Cell($pageWidth * 6 / 100, 4, 'P2', 1, 0, 'C');
                        $this->Cell($pageWidth * 6 / 100, 4, 'P3', 1, 0, 'C');
                        $this->Cell($pageWidth * 6 / 100, 4, 'P4', 1, 0, 'C');
                        $this->Cell($pageWidth * 6 / 100, 4, 'P5', 1, 0, 'C');
                        $this->Cell($pageWidth * 6 / 100, 4, 'P6', 1, 0, 'C');
                        $this->Cell($pageWidth * 6 / 100, 4, 'HAB', 1, 0, 'C');
                        $this->Cell($pageWidth * 10 / 100, 4, 'DEF', 1, 0, 'C');
                        $this->Ln();
                        $this->SetFont($this->fontfamilycontent, '', 10);
                        $observaciones = '';
                        if (is_array($calificaciones) && count($calificaciones) > 0) {
                            for ($j = 0; $j < count($calificaciones); $j++) {
                                $ih = '' . ($calificaciones[$j]['hteoricas_asignatura'] + $calificaciones[$j]['hpracticas_asignatura']);
                                $this->Cell($pageWidth * 42 / 100, 4, strtoupper($calificaciones[$j]['nombre_asignatura']), 1, 0, 'L');
                                $this->Cell($pageWidth * 6 / 100, 4, str_replace('0', '', $ih), 1, 0, 'C');
                                $this->Cell($pageWidth * 6 / 100, 4, str_replace('0.0', '', $calificaciones[$j]['np1']), 1, 0, 'C');
                                $this->Cell($pageWidth * 6 / 100, 4, str_replace('0.0', '', $calificaciones[$j]['np2']), 1, 0, 'C');
                                $this->Cell($pageWidth * 6 / 100, 4, str_replace('0.0', '', $calificaciones[$j]['np3']), 1, 0, 'C');
                                $this->Cell($pageWidth * 6 / 100, 4, str_replace('0.0', '', $calificaciones[$j]['np4']), 1, 0, 'C');
                                $this->Cell($pageWidth * 6 / 100, 4, str_replace('0.0', '', $calificaciones[$j]['np5']), 1, 0, 'C');
                                $this->Cell($pageWidth * 6 / 100, 4, str_replace('0.0', '', $calificaciones[$j]['np6']), 1, 0, 'C');
                                $this->Cell($pageWidth * 6 / 100, 4, str_replace('0.0', '', $calificaciones[$j]['nphab']), 1, 0, 'C');
                                $this->Cell($pageWidth * 10 / 100, 4, $calificaciones[$j]['def'], 1, 0, 'C');
                                $this->Ln();
                                $comentario = null;
                                if (isset($calificaciones[$j]['p' . $corte . '_comentarios_calificacion'])) {
                                    $comentario = $calificaciones[$j]['p' . $corte . '_comentarios_calificacion'];
                                }
                                if ($comentario !== null && $comentario !== '') {
                                    $observaciones = $observaciones . strtoupper($calificaciones[$j]['nombre_asignatura']) . ': ' . $comentario . "\n";
                                }
                            }
                        }
                        $this->Ln();
                        $this->SetFont($this->fontfamilycontent, 'B', 10);
                        $this->Cell($pageWidth, 4, 'Observaciones o Comentarios:');
                        $this->Ln();
                        $this->SetFont($this->fontfamilycontent, '', 10);
                        $this->MultiCell($pageWidth, 20, $observaciones, 1);
                        $this->Ln(4);
                        $this->SetFont($this->fontfamilycontent, 'B', 10);
                        $this->MultiCell($pageWidth / 2, 10, '________________________' . "\n" . 'Director de Grupo', 0, 'C', false, 0);
                        $this->MultiCell($pageWidth / 2, 10, '________________________' . "\n" . 'Cordinador Académico', 0, 'C', false, 0);
                        $this->Ln(4);
                        if ($i < (count($data) - 1)) {
                            $this->AddPage();
                        }
                    }
                }
            }
        }
    }

    public function InformeCalificacionesCompleto($idsede = null, $idjornada = null, $idprograma = null, $grado = null, $idgrupo = null, $idperiodo = null, $idcorte = null, $idestudiante = null) {
        $htmltable = '';
        $data = null;
        $calificaciones = null;
        $data = $this->bc->getInformesCalificaciones($this->session->getEnterpriseID(), $idsede, $idjornada, $idprograma, null, $grado, $idgrupo, $idperiodo, $idestudiante);
        $corte = $this->bc->getCortesPeriodos($this->session->getEnterpriseID(), $idperiodo, $idcorte);
        if ($corte !== null && $corte !== '[]') {
            $corte = json_decode($corte, true);
            $corte = $corte[0]['numero_corte'];
        }
        $pageWidth = $this->getRealPageWidth();
        if ($data !== null && $data !== '' && $data !== '[]') {
            $this->SetFont($this->fontfamilycontent, $this->fontstylecontent, $this->fontsizecontent);
            $data = json_decode($data, true);
            if ($data !== null && is_array($data)) {
                for ($i = 0; $i < count($data); $i++) {
                    $calificaciones = null;
                    if (isset($data[$i]['calificaciones'])) {
                        $calificaciones = $data[$i]['calificaciones'];
                        if ($calificaciones !== null && $calificaciones !== '' && $calificaciones !== '[]') {
                            $calificaciones = json_decode($calificaciones, true);
                        }
                    }
                    if ($calificaciones !== null && is_array($calificaciones)) {
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
                        $headtable = $headtable . '<td><b>' . strtoupper($data[$i]['nombrecompleto_estudiante']) . '</b></td>';
                        $headtable = $headtable . '<td>' . strtoupper($data[$i]['nombre_programa']) . '</td>';
                        $headtable = $headtable . '<td>' . strtoupper($data[$i]['nombre_sede']) . '</td>';
                        $headtable = $headtable . '<td>' . strtoupper($data[$i]['nombre_jornada']) . '</td>';
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
                        $headtable = $headtable . '<td>' . strtoupper($this->getSpanishOrdinalsNumbers($data[$i]['numgrado_programa'])) . '</td>';
                        $headtable = $headtable . '<td>' . $data[$i]['nombre_grupo'] . '</td>';
                        $headtable = $headtable . '<td>' . utf8_decode($data[$i]['nombre_director']) . '</td>';
                        $headtable = $headtable . '</tr>';
                        $headtable = $headtable . '</table>';
                        $this->writeHTML($headtable, true, false, false, false, '');

                        $htmltable = $htmltable . '<table border="1">';
                        $htmltable = $htmltable . '<tr>';
                        $htmltable = $htmltable . '<th style="text-align: justify; width: 26%;"><b>AREAS Y/O ASIGNATURA</b></th>';
                        $htmltable = $htmltable . '<th style="text-align: center; width: 4%;"><b>IH</b></th>';
                        $htmltable = $htmltable . '<th style="text-align: center; width: 4%;"><b>P1</b></th>';
                        $htmltable = $htmltable . '<th style="text-align: center; width: 4%;"><b>P2</b></th>';
                        $htmltable = $htmltable . '<th style="text-align: center; width: 4%;"><b>P3</b></th>';
                        $htmltable = $htmltable . '<th style="text-align: center; width: 4%;"><b>P4</b></th>';
                        $htmltable = $htmltable . '<th style="text-align: center; width: 4%;"><b>P5</b></th>';
                        $htmltable = $htmltable . '<th style="text-align: center; width: 4%;"><b>P6</b></th>';
                        $htmltable = $htmltable . '<th style="text-align: center; width: 4%;"><b>Acu</b></th>';
                        $htmltable = $htmltable . '<th style="text-align: center; width: 4%;"><b>Aus</b></th>';
                        $htmltable = $htmltable . '<th style="text-align: center; width: 38%;"><b>LOGROS / DIFICULTADES / RECOMENDACIONES </b></th>';
                        $htmltable = $htmltable . '</tr>';

                        if ($calificaciones !== null && is_array($calificaciones)) {
                            for ($j = 0; $j < count($calificaciones); $j++) {
                                $htmltable = $htmltable . '<tr>';
                                $htmltable = $htmltable . '<td><label style="font-size: 4pt; padding-top: 0px;">' . strtoupper($calificaciones[$j]['nombre_area']) . '</label> <br>'
                                        . '<b>' . strtoupper($calificaciones[$j]['nombre_asignatura']) . '</b></td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">' . ($calificaciones[$j]['hteoricas_asignatura'] + $calificaciones[$j]['hpracticas_asignatura']) . '</td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">' . str_replace('0.0', '', $calificaciones[$j]['np1']) . '</td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">' . str_replace('0.0', '', $calificaciones[$j]['np2']) . '</td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">' . str_replace('0.0', '', $calificaciones[$j]['np3']) . '</td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">' . str_replace('0.0', '', $calificaciones[$j]['np4']) . '</td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">' . str_replace('0.0', '', $calificaciones[$j]['np5']) . '</td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">' . str_replace('0.0', '', $calificaciones[$j]['np6']) . '</td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">' . $calificaciones[$j]['def'] . '</td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">';
                                if ($corte !== null) {
                                    $htmltable = $htmltable . $calificaciones[$j]['p' . $corte . '_ausencias_calificacion'];
                                }
                                $htmltable = $htmltable . '</td>';
                                $htmltable = $htmltable . '<td style="text-align: justify; font-size: 8pt;">';
                                if ($corte !== null && is_numeric($corte)) {
                                    if ($calificaciones[$j]['p' . $corte . '_descripcion_logroc'] !== '') {
                                        $htmltable = $htmltable . ' - ' . utf8_decode($calificaciones[$j]['p' . $corte . '_descripcion_logroc']) . '';
                                    }
                                    if ($calificaciones[$j]['p' . $corte . '_descripcion_logrop'] !== '') {
                                        $htmltable = $htmltable . '<br> - ' . utf8_decode($calificaciones[$j]['p' . $corte . '_descripcion_logrop']) . '';
                                    }
                                    if ($calificaciones[$j]['p' . $corte . '_descripcion_logroa'] !== '') {
                                        $htmltable = $htmltable . '<br> - ' . utf8_decode($calificaciones[$j]['p' . $corte . '_descripcion_logroa']) . '';
                                    }
                                    if ($calificaciones[$j]['p' . $corte . '_comentarios_calificacion'] !== '') {
                                        $htmltable = $htmltable . '<br> - ' . utf8_decode($calificaciones[$j]['p' . $corte . '_comentarios_calificacion']) . '';
                                    }
                                }
                                $htmltable = $htmltable . '</td>';

                                $htmltable = $htmltable . '</tr>';
                            }
                        }
                        $htmltable = $htmltable . '</table>';
                        $this->SetFontSize($this->fontsizecontent);
                        $this->writeHTML($htmltable, true, false, false, false, '');
                        $this->Ln(2);
                        $this->SetFont($this->fontfamilycontent, 'B', 10);
                        $this->MultiCell($pageWidth * 70 / 100, 20, 'Observaciones', 1, 'C', false, 0);
                        $this->SetFont($this->fontfamilycontent, 'B', 8);
                        $this->MultiCell($pageWidth * 30 / 100, 10, '______________________________' . "\n" . 'Director de Grupo' . "\n\n" . '______________________________' . "\n" . 'Cordinador Académico', 0, 'C', false, 0);
                        $this->Ln(4);
                        $htmltable = '';
                        if ($i < (count($data) - 1)) {
                            $this->AddPage();
                        }
                    }
                }
            }
        }
    }

    public function ObservadorEstudiante($idestudiante) {
        $data = null;
        $periodos = null;
        $data = $this->bc->getObservadorEstudiante($this->session->getEnterpriseID(), $idestudiante);
        $periodos = $this->bc->getPeriodosAnuales($this->session->getEnterpriseID());
        $pageWidth = $this->getRealPageWidth();
        if ($periodos !== null && $periodos !== '' && $periodos !== '[]') {
            $periodos = json_decode($periodos, true);
        }
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
                for ($i = 0; $i < count($data); $i++) {
                    $this->SetFont($this->fontfamilycontent, 'B', 18);
                    $this->Cell(0, 6, 'OBSERVADOR DE ESTUDIANTE', 0, 1, 'C');
                    $this->Ln(4);
                    $this->SetFont($this->fontfamilycontent, '', 10);
                    $this->Cell($pageWidth * 25 / 100, 4, 'PRIMER APELLIDO', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'SEGUNDO APELLIDO', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'PRIMER NOMBRE', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'SEGUNDO NOMBRE', 0, 0, 'C');
                    $this->Ln(4);
                    $this->SetFont($this->fontfamilycontent, 'B', 12);
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['apellido1_persona']), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['apellido2_persona']), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['nombre1_persona']), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['nombre2_persona']), 0, 0, 'C');
                    $this->Ln(8);
                    $this->SetFont($this->fontfamilycontent, '', 10);
                    $this->Cell($pageWidth * 25 / 100, 4, 'SEXO', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'EDAD', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'FECHA NACIMIENTO', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'CODIGO', 0, 0, 'C');
                    $this->Ln(4);
                    $this->SetFont($this->fontfamilycontent, 'B', 12);
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($this->getSpanishGender($data[$i]['sexo_persona'])), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['edad_persona'] . ' AÑOS'), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['fechanacimiento_persona']), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['id_persona']), 0, 0, 'C');
                    $this->Ln(8);
                    $this->SetFont($this->fontfamilycontent, '', 10);
                    $this->Cell($pageWidth * 25 / 100, 4, 'TIPO IDENTIFICACION', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'NUMERO IDENTIFICACION', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'TELEFONO', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'EMAIL', 0, 0, 'C');
                    $this->Ln(4);
                    $this->SetFont($this->fontfamilycontent, 'B', 12);
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['tipodoc_persona']), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['documento_persona']), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['telefono_persona']), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['email_persona']), 0, 0, 'C');
                    $this->Ln(8);
                    $this->SetFont($this->fontfamilycontent, '', 10);
                    $this->Cell($pageWidth * 25 / 100, 4, 'PAIS', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'DEPARTAMENTO', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'MUNICIPIO', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'DIRECCION', 0, 0, 'C');
                    $this->Ln(4);
                    $this->SetFont($this->fontfamilycontent, 'B', 12);
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['pais_persona']), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['departamento_persona']), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['ciudad_persona']), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['direccion_persona']), 0, 0, 'C');
                    $this->Ln(8);
                    $this->SetFont($this->fontfamilycontent, '', 10);
                    $this->Cell($pageWidth * 25 / 100, 4, 'PESO (kg)', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'ESTATURA (cm)', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'GRUPO SANGUINEO', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'FACTOR RH', 0, 0, 'C');
                    $this->Ln(4);
                    $this->SetFont($this->fontfamilycontent, 'B', 12);
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['pesokg_estudiante']), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['estaturam_estudiante']), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['grupotiposangre_estudiante']), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['rhtiposangre_estudiante']), 0, 0, 'C');
                    $this->Ln(8);
                    $this->SetFont($this->fontfamilycontent, '', 10);
                    $this->Cell($pageWidth * 25 / 100, 4, 'DEF. VISUAL', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'DEF. AUDITIVA', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'ALERGIAS', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, 'ENF. CRONICA', 0, 0, 'C');
                    $this->Ln(4);
                    $this->SetFont($this->fontfamilycontent, 'B', 12);
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($this->getSpanishBoolean($data[$i]['deficienciavisual_estudiante'])), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($this->getSpanishBoolean($data[$i]['deficienciaauditiva_estudiante'])), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['alergias_estudiante']), 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 4, strtoupper($data[$i]['enfermedadcronica_estudiante']), 0, 0, 'C');
                    $this->Ln(10);
                    $this->SetFont($this->fontfamilycontent, 'B', 14);
                    $this->Cell($pageWidth, 4, 'HISTORIAL DE NOTAS', 0, 0, 'C');
                    $this->Ln(2);
                    $calificaciones = null;
                    if (is_array($periodos) && count($periodos) > 0) {
                        if (isset($data[$i]['calificaciones'])) {
                            $calificaciones = $data[$i]['calificaciones'];
                            if ($calificaciones !== null && $calificaciones !== '' && $calificaciones !== '[]') {
                                $calificaciones = json_decode($calificaciones, true);
                            }
                        }
                        if ($calificaciones !== null && is_array($calificaciones)) {
                            for ($j = 0; $j < count($periodos); $j++) {
                                $labelperiodo = true;
                                $ok = false;
                                for ($k = 0; $k < count($calificaciones); $k++) {
                                    if ($calificaciones[$k]['id_periodo'] == $periodos[$j]['id_periodo']) {
                                        if ($labelperiodo == true) {
                                            $this->Ln(4);
                                            $this->SetFont($this->fontfamilycontent, 'B', 12);
                                            $this->Cell($pageWidth, 4, 'Año: ' . $calificaciones[$k]['id_periodo'] . ' - Programa: ' . $calificaciones[$k]['id_programa'] . ' - Grado: ' . $calificaciones[$k]['numgrado_programa'] . ' - Grupo: ' . $calificaciones[$k]['id_grupo'], 0, 0, 'C');
                                            $this->Ln(8);
                                            $this->SetFont($this->fontfamilycontent, 'B', 12);
                                            $this->Cell($pageWidth * 42 / 100, 4, 'ASIGNATURA', 1, 0, 'L');
                                            $this->Cell($pageWidth * 7 / 100, 4, 'P1', 1, 0, 'C');
                                            $this->Cell($pageWidth * 7 / 100, 4, 'P2', 1, 0, 'C');
                                            $this->Cell($pageWidth * 7 / 100, 4, 'P3', 1, 0, 'C');
                                            $this->Cell($pageWidth * 7 / 100, 4, 'P4', 1, 0, 'C');
                                            $this->Cell($pageWidth * 7 / 100, 4, 'P5', 1, 0, 'C');
                                            $this->Cell($pageWidth * 7 / 100, 4, 'P6', 1, 0, 'C');
                                            $this->Cell($pageWidth * 7 / 100, 4, 'HAB', 1, 0, 'C');
                                            $this->Cell($pageWidth * 9 / 100, 4, 'DEF', 1, 0, 'C');
                                            $this->Ln();
                                            $labelperiodo = false;
                                        }
                                        $this->SetFont($this->fontfamilycontent, '', 10);
                                        $this->Cell($pageWidth * 42 / 100, 4, $calificaciones[$k]['nombre_asignatura'], 1, 0, 'L');
                                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$k]['np1']), 1, 0, 'C');
                                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$k]['np2']), 1, 0, 'C');
                                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$k]['np3']), 1, 0, 'C');
                                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$k]['np4']), 1, 0, 'C');
                                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$k]['np5']), 1, 0, 'C');
                                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$k]['np6']), 1, 0, 'C');
                                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$k]['nphab']), 1, 0, 'C');
                                        $this->SetFont($this->fontfamilycontent, 'B', 10);
                                        $this->Cell($pageWidth * 9 / 100, 4, $calificaciones[$k]['npfin'], 1, 0, 'C');
                                        $this->Ln();
                                    }
                                }
                            }
                        }
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
