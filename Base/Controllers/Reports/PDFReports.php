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
        $ordinals = array("TRANSICION", "PRIMERO", "SEGUNDO", "TERCERO", "CUARTO", "QUINTO", "SEXTO", "SEPTIMO", "OCTAVO", "NOVENO", "DÉCIMO", "UNDECIMO", "DUODECIMO");
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
            $this->SetY(-10);
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
        $pageWidth = $this->getRealPageWidth();
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
                $this->Cell($pageWidth * 10 / 100, 8, 'CODIGO');
                $this->Cell($pageWidth * 40 / 100, 8, 'NOMBRE DE ESCUELA');
                $this->Cell($pageWidth * 40 / 100, 8, 'DIRECCION');
                $this->Cell($pageWidth * 10 / 100, 8, 'TELEFONO');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['id_escuela']);
                    $this->Cell($pageWidth * 40 / 100, 6, $data[$i]['nombre_escuela']);
                    $this->Cell($pageWidth * 40 / 100, 6, $data[$i]['direccion_escuela']);
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['telefono_escuela']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoSedes($idescuela = null) {
        $pageWidth = $this->getRealPageWidth();
        $data = $this->bc->getSedes($idescuela);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE SEDES');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell($pageWidth * 10 / 100, 8, 'CODIGO');
                $this->Cell($pageWidth * 40 / 100, 8, 'NOMBRE DE SEDE');
                $this->Cell($pageWidth * 40 / 100, 8, 'DIRECCION');
                $this->Cell($pageWidth * 10 / 100, 8, 'TELEFONO');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['id_sede']);
                    $this->Cell($pageWidth * 40 / 100, 6, $data[$i]['nombre_sede']);
                    $this->Cell($pageWidth * 40 / 100, 6, $data[$i]['direccion_sede']);
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['telefono_sede']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoProgramas($idescuela = null) {
        $pageWidth = $this->getRealPageWidth();
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
                $this->Cell($pageWidth * 10 / 100, 8, 'CODIGO');
                $this->Cell($pageWidth * 40 / 100, 8, 'NOMBRE DE PROGRAMA');
                $this->Cell($pageWidth * 20 / 100, 8, 'NIVEL');
                $this->Cell($pageWidth * 10 / 100, 8, 'GRADOS');
                $this->Cell($pageWidth * 20 / 100, 8, 'PERIODICIDAD');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['id_programa']);
                    $this->Cell($pageWidth * 40 / 100, 6, $data[$i]['nombre_programa']);
                    $this->Cell($pageWidth * 20 / 100, 6, $data[$i]['nivel_programa']);
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['ngrados_programa']);
                    $this->Cell($pageWidth * 20 / 100, 6, $data[$i]['periodicidadgrado_programa']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoAreas($idescuela = null) {
        $pageWidth = $this->getRealPageWidth();
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
                $this->Cell($pageWidth * 10 / 100, 8, 'CODIGO');
                $this->Cell($pageWidth * 90 / 100, 8, 'NOMBRE DE AREA');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['id_area']);
                    $this->Cell($pageWidth * 90 / 100, 6, $data[$i]['nombre_area']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoAsignaturas($idescuela = null) {
        $pageWidth = $this->getRealPageWidth();
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
                $this->Cell($pageWidth * 10 / 100, 8, 'CODIGO');
                $this->Cell($pageWidth * 70 / 100, 8, 'NOMBRE DE ASIGNATURA');
                $this->Cell($pageWidth * 20 / 100, 8, 'AREA');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['id_asignatura']);
                    $this->Cell($pageWidth * 70 / 100, 6, $data[$i]['nombre_asignatura']);
                    $this->Cell($pageWidth * 20 / 100, 6, $data[$i]['id_area']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoDetalladoPlanEstudio($idescuela = null, $idprograma, $idplanestudio) {
        $data = null;
        $data = $this->bc->getPlanEstudioDetalle($idescuela, $idprograma, $idplanestudio);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
                $pageWidth = $this->getRealPageWidth();
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'PLAN DE ESTUDIOS DETALLADO');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell($pageWidth * 10 / 100, 8, 'GRADO', true);
                $this->Cell($pageWidth * 12 / 100, 8, 'CODIGO', true);
                $this->Cell($pageWidth * 46 / 100, 8, 'NOMBRE DE ASIGNATURA', true);
                $this->Cell($pageWidth * 10 / 100, 8, 'HT', true);
                $this->Cell($pageWidth * 10 / 100, 8, 'HP', true);
                $this->Cell($pageWidth * 12 / 100, 8, 'HORAS', true);

                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['numgrado_programa'] . '°', true);
                    $this->Cell($pageWidth * 12 / 100, 6, $data[$i]['id_asignatura'], true);
                    $this->SetFont($this->fontfamilycontent, 'B', $this->fontsizecontent);
                    $this->Cell($pageWidth * 46 / 100, 6, $data[$i]['nombre_asignatura'], true);
                    $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['hteoricas_asignatura'], true);
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['hpracticas_asignatura'], true);
                    $this->Cell($pageWidth * 12 / 100, 6, ($data[$i]['hteoricas_asignatura'] + $data[$i]['hpracticas_asignatura']), true);
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
                $pageWidth = $this->getRealPageWidth();
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE LOGROS Y/O COMPETENCIAS');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell($pageWidth * 10 / 100, 4, 'CODIGO', 1);
                $this->Cell($pageWidth * 40 / 100, 4, 'NOMBRE DE ASIGNATURA', 1);
                $this->Cell($pageWidth * 10 / 100, 4, 'TIPO', 1);
                $this->Cell($pageWidth * 10 / 100, 4, 'MINIMO', 1);
                $this->Cell($pageWidth * 10 / 100, 4, 'MAXIMO', 1);
                $this->Cell($pageWidth * 10 / 100, 4, 'GRADO', 1);
                $this->Cell($pageWidth * 10 / 100, 4, 'PERIODO', 1);
                $this->Ln();
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($data); $i++) {
                    $this->SetFont($this->fontfamilycontent, 'B', $this->fontsizecontent);
                    $this->Cell($pageWidth * 10 / 100, 4, $data[$i]['id_logro'], 1);
                    $this->Cell($pageWidth * 40 / 100, 4, $data[$i]['nombre_asignatura'], 1);
                    $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                    $this->Cell($pageWidth * 10 / 100, 4, $data[$i]['tipo_logro'], 1);
                    $this->Cell($pageWidth * 10 / 100, 4, $data[$i]['min_logro'], 1);
                    $this->Cell($pageWidth * 10 / 100, 4, $data[$i]['max_logro'], 1);
                    $this->Cell($pageWidth * 10 / 100, 4, $data[$i]['numgrado_logro'], 1);
                    $this->Cell($pageWidth * 10 / 100, 4, $data[$i]['numcorte_logro'], 1);
                    $this->Ln();
                    $this->MultiCell($pageWidth * 100 / 100, 4, $data[$i]['descripcion_logro'], 1, 'L');
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoGrupos($idescuela = null, $idprograma = null, $numgrado = null) {
        $pageWidth = $this->getRealPageWidth();
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
                $this->Cell($pageWidth * 14 / 100, 8, 'CODIGO');
                $this->Cell($pageWidth * 50 / 100, 8, 'PROGRAMA');
                $this->Cell($pageWidth * 12 / 100, 8, 'SEDE');
                $this->Cell($pageWidth * 12 / 100, 8, 'GRADO');
                $this->Cell($pageWidth * 12 / 100, 8, 'GRUPO');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell($pageWidth * 14 / 100, 6, $data[$i]['id_grupo']);
                    $this->Cell($pageWidth * 50 / 100, 6, $data[$i]['nombre_programa']);
                    $this->Cell($pageWidth * 12 / 100, 6, $data[$i]['id_sede']);
                    $this->Cell($pageWidth * 12 / 100, 6, $data[$i]['numgrado_programa'] . '°');
                    $this->Cell($pageWidth * 12 / 100, 6, $data[$i]['numgrado_programa'] . $data[$i]['num_grupo']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoDocentes() {
        $pageWidth = $this->getRealPageWidth();
        $data = null;
        $data = $this->bc->getDocentes();
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', 14);
                $this->Cell(0, 8, 'LISTADO DE DOCENTES', 0, 0, 'C');
                $this->SetFont($this->fontfamilycontent, 'B', 10);
                $this->Ln();
                $this->Cell($pageWidth * 40 / 100, 6, 'NOMBRE COMPLETO', 1);
                $this->Cell($pageWidth * 10 / 100, 6, 'DOCUMENTO', 1);
                $this->Cell($pageWidth * 4 / 100, 6, 'SEXO', 1);
                $this->Cell($pageWidth * 4 / 100, 6, 'EDAD', 1);
                $this->Cell($pageWidth * 32 / 100, 6, 'TITULO ACADEMICO', 1);
                $this->Cell($pageWidth * 10 / 100, 6, 'ESCALAFON', 1);
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', 10);
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell($pageWidth * 40 / 100, 6, $data[$i]['nombrecompleto_docente'], 1);
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['tipodoc_persona'] . ' ' . $data[$i]['documento_persona'], 1);
                    $this->Cell($pageWidth * 4 / 100, 6, $data[$i]['sexo_persona'], 1);
                    $this->Cell($pageWidth * 4 / 100, 6, $data[$i]['edad_persona'], 1);
                    $this->Cell($pageWidth * 32 / 100, 6, $data[$i]['ultimotitulo_docente'], 1);
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['escalafon_docente'], 1);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoContactosDocentes() {
        $pageWidth = $this->getRealPageWidth();
        $data = null;
        $data = $this->bc->getContactosDocentes();
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', 14);
                $this->Cell(0, 8, 'DATOS DE CONTACTO DE DOCENTES', 0, 0, 'C');
                $this->SetFont($this->fontfamilycontent, 'B', 10);
                $this->Ln();
                $this->Cell($pageWidth * 8 / 100, 6, 'CODIGO', 1);
                $this->Cell($pageWidth * 32 / 100, 6, 'NOMBRE Y APELLIDO', 1);
                $this->Cell($pageWidth * 8 / 100, 6, 'TELEFONO', 1);
                $this->Cell($pageWidth * 28 / 100, 6, 'DIRECCION', 1);
                $this->Cell($pageWidth * 24 / 100, 6, 'EMAIL', 1);
                $this->Ln();
                for ($i = 0; $i < count($data); $i++) {
                    $this->SetFont($this->fontfamilycontent, '', 10);
                    $this->Cell($pageWidth * 8 / 100, 6, $data[$i]['id_docente'], 1);
                    $this->Cell($pageWidth * 32 / 100, 6, $data[$i]['nombre1_persona'] . ' ' . $data[$i]['apellido1_persona'], 1);
                    $this->Cell($pageWidth * 8 / 100, 6, $data[$i]['telefono_persona'], 1);
                    $this->Cell($pageWidth * 28 / 100, 6, $data[$i]['direccion_persona'] . ' ' . $data[$i]['ciudad_persona'], 1);
                    $this->SetFont($this->fontfamilycontent, '', 8);
                    $this->Cell($pageWidth * 24 / 100, 6, $data[$i]['email_persona'], 1);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoCargasDocentes($idescuela = null, $idperiodo = null, $idprograma = null, $iddocente = null, $idgrupo = null) {
        $pageWidth = $this->getRealPageWidth();
        $data = $this->bc->getCargasDocentes($idescuela, $idperiodo, $idprograma, $iddocente, $idgrupo);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'CARGA ACADEMICA DOCENTE');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell($pageWidth * 35 / 100, 8, 'NOMBRE COMPLETO DOCENTE', true);
                $this->Cell($pageWidth * 20 / 100, 8, 'PROGRAMA', true);
                $this->Cell($pageWidth * 25 / 100, 8, 'ASIGNATURA', true);
                $this->Cell($pageWidth * 10 / 100, 8, 'GRUPO', true);
                $this->Cell($pageWidth * 5 / 100, 8, 'HORAS', true);
                $this->Cell($pageWidth * 5 / 100, 8, 'AÑO', true);
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell($pageWidth * 35 / 100, 6, $data[$i]['nombrecompleto_docente'], true);
                    $this->Cell($pageWidth * 20 / 100, 6, $data[$i]['nombre_programa'], true);
                    $this->Cell($pageWidth * 25 / 100, 6, $data[$i]['nombre_asignatura'], true);
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['id_grupo'], true);
                    $this->Cell($pageWidth * 5 / 100, 6, $data[$i]['numhoras_carga'], true);
                    $this->Cell($pageWidth * 5 / 100, 6, $data[$i]['id_periodo'], true);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoDirectoresGrupos($idescuela = null, $idgrupo = null, $idperiodo = null) {
        $pageWidth = $this->getRealPageWidth();
        $data = $this->bc->getDirectoresGrupos($idescuela, $idgrupo, $idperiodo);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE DIRECTORES DE GRUPOS');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell($pageWidth * 10 / 100, 8, 'CODIGO', 1);
                $this->Cell($pageWidth * 50 / 100, 8, 'NOMBRE COMPLETO', 1);
                $this->Cell($pageWidth * 20 / 100, 8, 'GRUPO', 1);
                $this->Cell($pageWidth * 20 / 100, 8, 'PERIODO', 1);
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['id_docente'], 1);
                    $this->Cell($pageWidth * 50 / 100, 6, $data[$i]['nombrecompleto_docente'], 1);
                    $this->Cell($pageWidth * 20 / 100, 6, $data[$i]['id_grupo'], 1);
                    $this->Cell($pageWidth * 20 / 100, 6, $data[$i]['id_periodo'], 1);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoEstudiantes($idescuela = null, $idsede = null, $idjornada = null, $idprograma = null, $numgrado = null, $idgrupo = null, $idperiodo = null) {
        $pageWidth = $this->getRealPageWidth();
        $data = $this->bc->getEstudiantesMatriculas($idescuela, $idsede, $idjornada, $idprograma, null, $numgrado, $idgrupo, $idperiodo);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 4));
                $this->Cell(0, 8, 'LISTADO DE ESTUDIANTES');
                $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                $this->Ln();
                $this->Cell($pageWidth * 10 / 100, 8, 'CODIGO', 1);
                $this->Cell($pageWidth * 40 / 100, 8, 'NOMBRE COMPLETO ESTUDIANTE', 1);
                $this->Cell($pageWidth * 10 / 100, 8, 'AÑO', 1);
                $this->Cell($pageWidth * 20 / 100, 8, 'PROGRAMA', 1);
                $this->Cell($pageWidth * 10 / 100, 8, 'GRADO', 1);
                $this->Cell($pageWidth * 10 / 100, 8, 'GRUPO', 1);
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', $this->fontsizecontent);
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['id_estudiante'], 1);
                    $this->Cell($pageWidth * 40 / 100, 6, $data[$i]['nombrecompleto_estudiante'], 1);
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['anualidad_periodo'], 1);
                    $this->Cell($pageWidth * 20 / 100, 6, $data[$i]['nombre_programa'], 1);
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['numgrado_programa'] . '°', 1);
                    $this->Cell($pageWidth * 10 / 100, 6, $data[$i]['nombre_grupo'], 1);
                    $this->Ln();
                }
            }
        }
    }

    public function Matriculas($idescuela = null, $idsede = null, $idjornada = null, $idprograma = null, $idplanestudio = null, $numgrado = null, $idgrupo = null, $idperiodo = null, $idestudiante = null) {
        $pageWidth = $this->getRealPageWidth();
        $data = $this->bc->getMatriculas($idescuela, $idsede, $idjornada, $idprograma, $idplanestudio, $numgrado, $idgrupo, $idperiodo, $idestudiante);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
                for ($i = 0; $i < count($data); $i++) {
                    $this->SetFont($this->fontfamilycontent, 'B', 14);
                    $this->Cell($pageWidth, 8, 'RECIBO DE MATRICULA', 0, 0, 'C');
                    $this->SetFont($this->fontfamilycontent, '', 14);
                    $this->Ln();
                    $this->Cell($pageWidth, 8, $data[$i]['id_matricula'], 0, 0, 'C');
                    $this->SetFont($this->fontfamilycontent, 'B', 14);
                    $this->Ln();
                    $this->Cell($pageWidth, 8, $data[$i]['nombrecompleto_estudiante'] . ' (' . $data[$i]['id_estudiante'] . ')', 0, 0, 'C');
                    $this->SetFont($this->fontfamilycontent, 'B', 12);
                    $this->Ln();
                    $this->Cell($pageWidth * 50 / 100, 8, 'ESCUELA', 0, 0, 'C');
                    $this->Cell($pageWidth * 50 / 100, 8, 'SEDE', 0, 0, 'C');
                    $this->SetFont($this->fontfamilycontent, '', 12);
                    $this->Ln();
                    $this->Cell($pageWidth * 50 / 100, 8, $data[$i]['nombre_escuela'], 0, 0, 'C');
                    $this->Cell($pageWidth * 50 / 100, 8, $data[$i]['nombre_sede'], 0, 0, 'C');
                    $this->SetFont($this->fontfamilycontent, 'B', 12);
                    $this->Ln();
                    $this->Cell($pageWidth * 50 / 100, 8, 'PROGRAMA', 0, 0, 'C');
                    $this->Cell($pageWidth * 50 / 100, 8, 'PLAN DE ESTUDIOS', 0, 0, 'C');
                    $this->SetFont($this->fontfamilycontent, '', 12);
                    $this->Ln();
                    $this->Cell($pageWidth * 50 / 100, 8, $data[$i]['nombre_programa'], 0, 0, 'C');
                    $this->Cell($pageWidth * 50 / 100, 8, $data[$i]['descripcion_planestudio'], 0, 0, 'C');
                    $this->SetFont($this->fontfamilycontent, 'B', 12);
                    $this->Ln();
                    $this->Cell($pageWidth * 25 / 100, 8, 'JORNADA', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 8, 'GRADO', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 8, 'GRUPO', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 8, 'AÑO', 0, 0, 'C');
                    $this->SetFont($this->fontfamilycontent, '', 12);
                    $this->Ln();
                    $this->Cell($pageWidth * 25 / 100, 8, $data[$i]['nombre_jornada'], 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 8, $data[$i]['numgrado_programa'], 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 8, $data[$i]['id_grupo'], 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 8, $data[$i]['id_periodo'], 0, 0, 'C');
                    $this->SetFont($this->fontfamilycontent, 'B', 12);
                    $this->Ln();
                    $this->Cell($pageWidth * 25 / 100, 8, 'ESTADO', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 8, 'VALOR', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 8, 'FECHA MATRICULA', 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 8, 'F. ACTUALIZACION', 0, 0, 'C');
                    $this->SetFont($this->fontfamilycontent, '', 12);
                    $this->Ln();
                    $this->Cell($pageWidth * 25 / 100, 8, $data[$i]['estado_matricula'], 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 8, '$' . $data[$i]['valor_matricula'], 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 8, $data[$i]['fecha_matricula'], 0, 0, 'C');
                    $this->Cell($pageWidth * 25 / 100, 8, $data[$i]['fechaedita_matricula'], 0, 0, 'C');
                    $this->SetFont($this->fontfamilycontent, 'B', 12);
                    $this->Ln();
                    $matriculadas = $this->bc->getAsignaturasMatriculadas($data[$i]['id_escuela'], $data[$i]['id_matricula']);
                    if ($matriculadas !== null && $matriculadas !== '' && $matriculadas !== '[]') {
                        $matriculadas = json_decode($matriculadas, true);
                        $this->SetFont($this->fontfamilycontent, 'B', ($this->fontsizecontent + 2));
                        $this->Ln();
                        $this->Cell($pageWidth * 60 / 100, 8, 'ASIGNATURA', 1, 0, 'C');
                        $this->Cell($pageWidth * 20 / 100, 8, 'H.TEORICAS', 1, 0, 'C');
                        $this->Cell($pageWidth * 20 / 100, 8, 'H.PRACTICAS', 1, 0, 'C');
                        $this->SetFont($this->fontfamilycontent, '', ($this->fontsizecontent));
                        $this->Ln();
                        for ($j = 0; $j < count($matriculadas); $j++) {
                            $this->Cell($pageWidth * 60 / 100, 6, $matriculadas[$j]['nombre_asignatura'], 1, 0, 'C');
                            $this->Cell($pageWidth * 20 / 100, 6, $matriculadas[$j]['hteoricas_asignatura'], 1, 0, 'C');
                            $this->Cell($pageWidth * 20 / 100, 6, $matriculadas[$j]['hpracticas_asignatura'], 1, 0, 'C');
                            $this->Ln();
                        }
                    }
                    $this->SetFont($this->fontfamilycontent, 'B', 12);
                    $this->Ln();
                    $this->Cell($pageWidth * 100 / 100, 8, 'NOTAS', 0, 0, 'C');
                    $this->Ln();
                    $this->MultiCell($pageWidth * 100 / 100, 8, $data[$i]['comentarios_matricula'], 1, '');
                    $this->Ln();
                    $this->Cell($pageWidth * 50 / 100, 8, '_____________________________', 0, 0, 'C');
                    $this->Cell($pageWidth * 50 / 100, 8, '_____________________________', 0, 0, 'C');
                    $this->Ln();
                    $this->Cell($pageWidth * 50 / 100, 8, 'DIRECTIVO / DOCENTE', 0, 0, 'C');
                    $this->Cell($pageWidth * 50 / 100, 8, 'PADRE / ACUDIENTE', 0, 0, 'C');

                    if ($i < (count($data) - 1)) {
                        $this->AddPage();
                    }
                }
            }
        }
    }

    public function ListadoPlanillaEstudiantes($idescuela, $idprograma, $idplanestudio, $idperiodo, $idcorte, $numgrado = null, $idgrupo = null) {
        $pageWidth = $this->getRealPageWidth();
        $asignaturas = $this->bc->getPlanEstudioDetalle($idescuela, $idprograma, $numgrado, $idplanestudio);
        if ($asignaturas !== null && $asignaturas !== '' && $asignaturas !== '[]') {
            $asignaturas = json_decode($asignaturas, true);
            for ($i = 0; $i < count($asignaturas); $i++) {
                $data = $this->bc->getAsignaturasMatriculadas($idescuela, null, null, $idprograma, $asignaturas[$i]['id_asignatura'], $idperiodo, $asignaturas[$i]['numgrado_programa'], $idgrupo);
                if ($data !== null && $data !== '' && $data !== '[]') {
                    $data = json_decode($data, true);
                    if (is_array($data) && count($data) > 0) {
                        $this->SetFont($this->fontfamilycontent, 'B', 14);
                        $this->Cell(0, 8, 'PLANILLA DE CALIFICACIONES', 0, 0, 'C');
                        $this->SetFont($this->fontfamilycontent, 'B', 12);
                        $this->Ln();
                        $this->Cell($pageWidth * 55 / 100, 6, $asignaturas[$i]['nombre_programa'], 0, 0, 'C');
                        $this->Cell($pageWidth * 15 / 100, 6, $asignaturas[$i]['numgrado_programa'] . '° GRADO', 0, 0, 'C');
                        $this->Cell($pageWidth * 15 / 100, 6, $idgrupo, 0, 0, 'C');
                        $this->Cell($pageWidth * 15 / 100, 6, $idcorte, 0, 0, 'C');
                        $this->SetFont($this->fontfamilycontent, 'B', 10);
                        $this->Ln();
                        $this->Cell($pageWidth * 33.5 / 100, 6, '' . $asignaturas[$i]['nombre_asignatura'], 1, 0, 'C');
                        $this->Cell($pageWidth * 25 / 100, 6, 'CALIFICACIONES', 1, 0, 'C');
                        $this->Cell($pageWidth * 42 / 100, 6, 'ASISTENCIA', 1, 0, 'C');
                        $this->SetFont($this->fontfamilycontent, 'B', 8);
                        $this->Ln();
                        $this->Cell($pageWidth * 1.5 / 100, 5, '#', 1);
                        $this->Cell($pageWidth * 32 / 100, 5, 'NOMBRE DE ESTUDIANTE', 1);
                        $this->Cell($pageWidth * 2.5 / 100, 5, 'CC1', 1);
                        $this->Cell($pageWidth * 2.5 / 100, 5, 'CC2', 1);
                        $this->Cell($pageWidth * 2.5 / 100, 5, 'CC3', 1);
                        $this->Cell($pageWidth * 2.5 / 100, 5, 'CP1', 1);
                        $this->Cell($pageWidth * 2.5 / 100, 5, 'CP2', 1);
                        $this->Cell($pageWidth * 2.5 / 100, 5, 'CP3', 1);
                        $this->Cell($pageWidth * 2.5 / 100, 5, 'CA1', 1);
                        $this->Cell($pageWidth * 2.5 / 100, 5, 'CA2', 1);
                        $this->Cell($pageWidth * 2.5 / 100, 5, 'CA3', 1);
                        $this->Cell($pageWidth * 2.5 / 100, 5, 'DEF', 1);
                        $this->Cell($pageWidth * 1 / 100, 5, '1', 1);
                        $this->Cell($pageWidth * 1 / 100, 5, '2', 1);
                        $this->Cell($pageWidth * 1 / 100, 5, '3', 1);
                        $this->Cell($pageWidth * 1 / 100, 5, '4', 1);
                        $this->Cell($pageWidth * 1 / 100, 5, '5', 1);
                        $this->Cell($pageWidth * 1 / 100, 5, '6', 1);
                        $this->Cell($pageWidth * 1 / 100, 5, '7', 1);
                        $this->Cell($pageWidth * 1 / 100, 5, '8', 1);
                        $this->Cell($pageWidth * 1 / 100, 5, '9', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '10', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '11', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '12', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '13', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '14', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '15', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '16', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '17', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '18', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '19', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '20', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '21', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '22', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '23', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '24', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '25', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '26', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '27', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '28', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '29', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '30', 1);
                        $this->Cell($pageWidth * 1.5 / 100, 5, '31', 1);
                        $this->Ln();
                        $this->SetFont($this->fontfamilycontent, '', 8);
                        for ($j = 0; $j < count($data); $j++) {
                            $this->SetFont($this->fontfamilycontent, '', 8);
                            $this->Cell($pageWidth * 1.5 / 100, 5, $j + 1, 1);
                            $this->Cell($pageWidth * 32 / 100, 5, $data[$j]['nombrecompleto_estudiante'], 1);
                            $this->SetFont($this->fontfamilycontent, '', 6);
                            $this->Cell($pageWidth * 2.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 2.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 2.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 2.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 2.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 2.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 2.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 2.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 2.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 2.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Cell($pageWidth * 1.5 / 100, 5, '', 1);
                            $this->Ln();
                        }
                        if ($i < count($asignaturas) - 1) {
                            $this->AddPage();
                        }
                    }
                }
            }
        }
    }

    public function ListadoContactosEstudiantes($idescuela, $idprograma, $idperiodo, $numgrado = null, $idgrupo = null, $idestudiante = null) {
        $data = null;
        $data = $this->bc->getContactosEstudiantesMatriculas($idescuela, $idprograma, $numgrado, $idgrupo, $idperiodo, $idestudiante);
        if ($data !== null && $data !== '' && $data !== '[]') {
            $data = json_decode($data, true);
            if (is_array($data) && count($data) > 0) {
                $pageWidth = $this->getRealPageWidth();
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', 14);
                $this->Cell(0, 8, 'DATOS DE CONTACTO DE ESTUDIANTES', 0, 0, 'C');
                $this->SetFont($this->fontfamilycontent, 'B', 8);
                $this->Ln();
                $this->Cell($pageWidth * 30 / 100, 6, 'NOMBRE COMPLETO', 1);
                $this->Cell($pageWidth * 5 / 100, 6, 'AÑO', 1);
                $this->Cell($pageWidth * 5 / 100, 6, 'GRUPO', 1);
                $this->Cell($pageWidth * 30 / 100, 6, 'DIRECCION', 1);
                $this->Cell($pageWidth * 20 / 100, 6, 'EMAIL', 1);
                $this->Cell($pageWidth * 10 / 100, 6, 'TELEFONO', 1);
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', 8);
                for ($i = 0; $i < count($data); $i++) {
                    $this->Cell($pageWidth * 30 / 100, 5, $data[$i]['nombrecompleto_estudiante'], 1);
                    $this->Cell($pageWidth * 5 / 100, 5, $data[$i]['id_periodo'], 1);
                    $this->Cell($pageWidth * 5 / 100, 5, $data[$i]['id_grupo'], 1);
                    $this->Cell($pageWidth * 30 / 100, 5, $data[$i]['direccion_persona'].' '.$data[$i]['ciudad_persona'], 1);
                    $this->Cell($pageWidth * 20 / 100, 5, $data[$i]['email_persona'], 1);
                    $this->Cell($pageWidth * 10 / 100, 5, $data[$i]['telefono_persona'], 1);
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

    public function CarnetsEstudiantiles($idescuela = null, $idsede = null, $idjornada = null, $idprograma = null, $numgrado = null, $idgrupo = null, $idperiodo = null, $idestudiante = null) {
        $data = null;
        $configuracion = null;
        $data = $this->bc->getEstudiantesMatriculas($idescuela, $idsede, $idjornada, $idprograma, null, $numgrado, $idgrupo, $idperiodo, $idestudiante);
        $configuracion = $this->bc->getConfiguracionEscuela($this->session->getEnterpriseID());
        if ($data !== null && $data !== '' && $data !== '[]' && $configuracion !== null && $configuracion !== '' && $configuracion !== '[]') {
            $data = json_decode($data, true);
            $configuracion = json_decode($configuracion, true);
            $configuracion = $configuracion[0];
            if (is_array($data) && count($data) > 0) {
                $this->SetFont($this->fontfamilycontent, '', 11);
                for ($i = 0; $i < count($data); $i++) {
                    $html = '';
                    $html = $html . '<table border="1" style="width: 100%; ">';
                    $html = $html . '<tr >';

                    $html = $html . '<td style="height: 140px !important;">';
                    $html = $html . '<table border="0">';
                    $html = $html . '<tr>';
                    $html = $html . '<td style="width: 70%; text-align: center;">';
                    $html = $html . '<label><b>Código:</b> ' . $data[$i]['id_estudiante'] . '</label>' . '<br>';
                    $html = $html . '<label><b>Nombres:</b><br> ' . $data[$i]['nombre1_persona'] . ' ' . $data[$i]['nombre2_persona'] . '</label>' . '<br>';
                    $html = $html . '<label>' . $data[$i]['apellido1_persona'] . ' ' . $data[$i]['apellido2_persona'] . '</label>' . '<br>';
                    $html = $html . '<label><b>' . $data[$i]['tipodoc_persona'] . ':</b> ' . $data[$i]['documento_persona'] . '</label>' . '<br>';
                    $html = $html . '<label><b>Programa:</b><br> ' . $data[$i]['nombre_programa'] . '</label>' . '<br>';
                    $html = $html . '<label><b>Grado:</b> ' . $this->getSpanishOrdinalsNumbers($data[$i]['numgrado_programa']) . '</label>' . '<br>';
                    $html = $html . '</td>';
                    $html = $html . '<td style="width: 30%;">';
                    $html = $html . '<img alt="Foto">';
                    $html = $html . '</td>';
                    $html = $html . '</tr>';
                    $html = $html . '</table>';
                    $html = $html . '</td>';

                    $html = $html . '<td style="text-align: center; vertical-align: middle;">';
                    $html = $html . '<b>' . strtoupper($configuracion['nombremostrar_configuracion']) . '</b><br>';
                    $html = $html . '<b>Carnet Estudiantil</b><br>';
                    $html = $html . 'Matricula: ' . $data[$i]['id_matricula'] . '.<br>';
                    $html = $html . 'Expedición: ' . $this->getSpanishActualDate() . '.<br>';
                    $html = $html . 'Año: ' . $data[$i]['id_periodo'] . '.<br>';
                    $html = $html . '<br>';
                    $html = $html . 'Este carnet es personal e intransferible.<br>';
                    $html = $html . '</td>';

                    $html = $html . '</tr>';
                    $html = $html . '</table>';
                    $this->writeHTML($html);
                    if (($i + 1) % 4 === 0 && $i !== 0) {
                        $this->AddPage();
                    }
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
                        $this->Cell($pageWidth * 7 / 100, 4, $calificaciones[$j]['p1_nd_calificacion'], 1, 0, 'C');
                        $this->Cell($pageWidth * 7 / 100, 4, $calificaciones[$j]['p2_nd_calificacion'], 1, 0, 'C');
                        $this->Cell($pageWidth * 7 / 100, 4, $calificaciones[$j]['p3_nd_calificacion'], 1, 0, 'C');
                        $this->Cell($pageWidth * 7 / 100, 4, $calificaciones[$j]['p4_nd_calificacion'], 1, 0, 'C');
                        $this->Cell($pageWidth * 7 / 100, 4, $calificaciones[$j]['p5_nd_calificacion'], 1, 0, 'C');
                        $this->Cell($pageWidth * 7 / 100, 4, $calificaciones[$j]['p6_nd_calificacion'], 1, 0, 'C');
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
                        $this->Cell(0, 6, 'INFORME DE CALIFICACIONES', 0, 1, 'C');
                        $this->Ln(2);
                        $this->SetFont($this->fontfamilycontent, '', 12);
                        $this->Cell($pageWidth * 66.6 / 100, 4, 'ESTUDIANTE', 0, 0, 'C');
                        $this->Cell($pageWidth * 33.3 / 100, 4, 'PROMEDIO', 0, 0, 'C');
                        $this->Ln(4);
                        $this->SetFont($this->fontfamilycontent, 'B', 14);
                        $this->Cell($pageWidth * 66.6 / 100, 4, strtoupper($data[$i]['nombrecompleto_estudiante']), 0, 0, 'C');
                        $this->Cell($pageWidth * 33.3 / 100, 4, strtoupper($data[$i]['Promedio']), 0, 0, 'C');
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
                                if ($calificaciones[$j]['p1_nd_calificacion'] === '0' || $calificaciones[$j]['p1_nd_calificacion'] === '0.0') {
                                    $calificaciones[$j]['p1_nd_calificacion'] = '';
                                }
                                if ($calificaciones[$j]['p2_nd_calificacion'] === '0' || $calificaciones[$j]['p2_nd_calificacion'] === '0.0') {
                                    $calificaciones[$j]['p2_nd_calificacion'] = '';
                                }
                                if ($calificaciones[$j]['p3_nd_calificacion'] === '0' || $calificaciones[$j]['p3_nd_calificacion'] === '0.0') {
                                    $calificaciones[$j]['p3_nd_calificacion'] = '';
                                }
                                if ($calificaciones[$j]['p4_nd_calificacion'] === '0' || $calificaciones[$j]['p4_nd_calificacion'] === '0.0') {
                                    $calificaciones[$j]['p4_nd_calificacion'] = '';
                                }
                                if ($calificaciones[$j]['p5_nd_calificacion'] === '0' || $calificaciones[$j]['p5_nd_calificacion'] === '0.0') {
                                    $calificaciones[$j]['p5_nd_calificacion'] = '';
                                }
                                if ($calificaciones[$j]['p6_nd_calificacion'] === '0' || $calificaciones[$j]['p6_nd_calificacion'] === '0.0') {
                                    $calificaciones[$j]['p6_nd_calificacion'] = '';
                                }
                                if ($calificaciones[$j]['phab_nd_calificacion'] === '0' || $calificaciones[$j]['phab_nd_calificacion'] === '0.0') {
                                    $calificaciones[$j]['phab_nd_calificacion'] = '';
                                }

                                $this->SetFont($this->fontfamilycontent, '', 10);
                                $ih = '' . ($calificaciones[$j]['hteoricas_asignatura'] + $calificaciones[$j]['hpracticas_asignatura']);
                                $this->Cell($pageWidth * 42 / 100, 4, strtoupper($calificaciones[$j]['nombre_asignatura']), 1, 0, 'L');
                                $this->Cell($pageWidth * 6 / 100, 4, $ih, 1, 0, 'C');
                                $this->Cell($pageWidth * 6 / 100, 4, $calificaciones[$j]['p1_nd_calificacion'], 1, 0, 'C');
                                $this->Cell($pageWidth * 6 / 100, 4, $calificaciones[$j]['p2_nd_calificacion'], 1, 0, 'C');
                                $this->Cell($pageWidth * 6 / 100, 4, $calificaciones[$j]['p3_nd_calificacion'], 1, 0, 'C');
                                $this->Cell($pageWidth * 6 / 100, 4, $calificaciones[$j]['p4_nd_calificacion'], 1, 0, 'C');
                                $this->Cell($pageWidth * 6 / 100, 4, $calificaciones[$j]['p5_nd_calificacion'], 1, 0, 'C');
                                $this->Cell($pageWidth * 6 / 100, 4, $calificaciones[$j]['p6_nd_calificacion'], 1, 0, 'C');
                                $this->Cell($pageWidth * 6 / 100, 4, $calificaciones[$j]['phab_nd_calificacion'], 1, 0, 'C');
                                $this->SetFont($this->fontfamilycontent, 'B', 10);
                                $this->Cell($pageWidth * 10 / 100, 4, $calificaciones[$j]['def'], 1, 0, 'C');
                                $this->Ln();
                                $comentario = null;
                                $this->SetFont($this->fontfamilycontent, '', 10);
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
                                if ($calificaciones[$j]['p1_nd_calificacion'] === '0' || $calificaciones[$j]['p1_nd_calificacion'] === '0.0') {
                                    $calificaciones[$j]['p1_nd_calificacion'] = '';
                                }
                                if ($calificaciones[$j]['p2_nd_calificacion'] === '0' || $calificaciones[$j]['p2_nd_calificacion'] === '0.0') {
                                    $calificaciones[$j]['p2_nd_calificacion'] = '';
                                }
                                if ($calificaciones[$j]['p3_nd_calificacion'] === '0' || $calificaciones[$j]['p3_nd_calificacion'] === '0.0') {
                                    $calificaciones[$j]['p3_nd_calificacion'] = '';
                                }
                                if ($calificaciones[$j]['p4_nd_calificacion'] === '0' || $calificaciones[$j]['p4_nd_calificacion'] === '0.0') {
                                    $calificaciones[$j]['p4_nd_calificacion'] = '';
                                }
                                if ($calificaciones[$j]['p5_nd_calificacion'] === '0' || $calificaciones[$j]['p5_nd_calificacion'] === '0.0') {
                                    $calificaciones[$j]['p5_nd_calificacion'] = '';
                                }
                                if ($calificaciones[$j]['p6_nd_calificacion'] === '0' || $calificaciones[$j]['p6_nd_calificacion'] === '0.0') {
                                    $calificaciones[$j]['p6_nd_calificacion'] = '';
                                }
                                $htmltable = $htmltable . '<tr>';
                                $htmltable = $htmltable . '<td><label style="font-size: 4pt; padding-top: 0px;">' . strtoupper($calificaciones[$j]['nombre_area']) . '</label> <br>'
                                        . '<b>' . strtoupper($calificaciones[$j]['nombre_asignatura']) . '</b></td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">' . ($calificaciones[$j]['hteoricas_asignatura'] + $calificaciones[$j]['hpracticas_asignatura']) . '</td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">' . $calificaciones[$j]['p1_nd_calificacion'] . '</td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">' . $calificaciones[$j]['p2_nd_calificacion'] . '</td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">' . $calificaciones[$j]['p3_nd_calificacion'] . '</td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">' . $calificaciones[$j]['p4_nd_calificacion'] . '</td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">' . $calificaciones[$j]['p5_nd_calificacion'] . '</td>';
                                $htmltable = $htmltable . '<td style="text-align: center;">' . $calificaciones[$j]['p6_nd_calificacion'] . '</td>';
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
                    if (is_array($periodos) && count($periodos) > 0) {
                        $anotaciones = null;
                        if (isset($data[$i]['anotaciones'])) {
                            $anotaciones = $data[$i]['anotaciones'];
                            if ($anotaciones !== null && $anotaciones !== '' && $anotaciones !== '[]') {
                                $anotaciones = json_decode($anotaciones, true);
                            }
                        }
                        if ($anotaciones !== null && is_array($anotaciones) && count($anotaciones) > 0) {
                            $this->AddPage('P');
                            $pageWidth = $this->getRealPageWidth();
                            $this->SetFont($this->fontfamilycontent, 'B', 14);
                            $this->Cell($pageWidth, 4, 'ANOTACIONES', 0, 0, 'C');
                            $this->Ln();
                            $this->writeHTML("<hr/>", false);
                            for ($a = 0; $a < count($anotaciones); $a++) {
                                $this->SetFont($this->fontfamilycontent, 'B', 12);
                                $this->Cell($pageWidth * 33.3 / 100, 4, 'ANOTACION', 0, 0, 'C');
                                $this->Cell($pageWidth * 33.3 / 100, 4, 'FECHA', 0, 0, 'C');
                                $this->Cell($pageWidth * 33.3 / 100, 4, 'TIPO', 0, 0, 'C');
                                $this->Ln();
                                $this->SetFont($this->fontfamilycontent, '', 10);
                                $this->Cell($pageWidth * 33.3 / 100, 4, $anotaciones[$a]['id_anotacion'], 0, 0, 'C');
                                $this->Cell($pageWidth * 33.3 / 100, 4, $anotaciones[$a]['fecha_anotacion'], 0, 0, 'C');
                                $this->Cell($pageWidth * 33.3 / 100, 4, $anotaciones[$a]['tipo_anotacion'], 0, 0, 'C');
                                $this->Ln(6);
                                $this->SetFont($this->fontfamilycontent, 'B', 10);
                                $this->Cell($pageWidth * 100 / 100, 4, 'DESCRIPCION', 0, 0, 'L');
                                $this->Ln();
                                $this->SetFont($this->fontfamilycontent, '', 10);
                                $this->MultiCell($pageWidth * 100 / 100, null, $anotaciones[$a]['descripcion_anotacion'], 0, 'L');
                                $this->Ln();
                                $this->SetFont($this->fontfamilycontent, 'B', 10);
                                $this->Cell($pageWidth * 100 / 100, 4, 'ESTRATEGIAS', 0, 0, 'L');
                                $this->Ln();
                                $this->SetFont($this->fontfamilycontent, '', 10);
                                $this->MultiCell($pageWidth * 100 / 100, null, $anotaciones[$a]['estrategias_anotacion'], 0, 'L');
                                $this->Ln();
                                $this->SetFont($this->fontfamilycontent, 'B', 10);
                                $this->Cell($pageWidth * 100 / 100, 4, 'COMPROMISOS DEL ACUDIENTE', 0, 0, 'L');
                                $this->Ln();
                                $this->SetFont($this->fontfamilycontent, '', 10);
                                $this->MultiCell($pageWidth * 100 / 100, null, $anotaciones[$a]['compromisosacudiente_anotacion'], 0, 'L');
                                $this->Ln();
                                $this->SetFont($this->fontfamilycontent, 'B', 10);
                                $this->Cell($pageWidth * 100 / 100, 4, 'COMPROMISOS DEL ESTUDIANTE', 0, 0, 'L');
                                $this->Ln();
                                $this->SetFont($this->fontfamilycontent, '', 10);
                                $this->MultiCell($pageWidth * 100 / 100, null, $anotaciones[$a]['compromisosestudiante_anotacion'], 0, 'L');
                                $this->Ln(4);
                                $this->writeHTML("<hr/>", false);
                            }
                        }

                        $calificaciones = null;

                        if (isset($data[$i]['calificaciones'])) {
                            $calificaciones = $data[$i]['calificaciones'];
                            if ($calificaciones !== null && $calificaciones !== '' && $calificaciones !== '[]') {
                                $calificaciones = json_decode($calificaciones, true);
                            }
                        }
                        if ($calificaciones !== null && is_array($calificaciones) && count($calificaciones) > 0) {
                            $this->AddPage('P');
                            $pageWidth = $this->getRealPageWidth();
                            $this->SetFont($this->fontfamilycontent, 'B', 14);
                            $this->Cell($pageWidth, 4, 'HISTORIAL DE NOTAS', 0, 0, 'C');
                            $this->Ln(2);

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
                                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$k]['p1_nd_calificacion']), 1, 0, 'C');
                                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$k]['p2_nd_calificacion']), 1, 0, 'C');
                                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$k]['p3_nd_calificacion']), 1, 0, 'C');
                                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$k]['p4_nd_calificacion']), 1, 0, 'C');
                                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$k]['p5_nd_calificacion']), 1, 0, 'C');
                                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0.0', '', $calificaciones[$k]['p6_nd_calificacion']), 1, 0, 'C');
                                        $this->Cell($pageWidth * 7 / 100, 4, str_replace('0', '', $calificaciones[$k]['phab_nd_calificacion']), 1, 0, 'C');
                                        $this->SetFont($this->fontfamilycontent, 'B', 10);
                                        $this->Cell($pageWidth * 9 / 100, 4, $calificaciones[$k]['pfin_nd_calificacion'], 1, 0, 'C');
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

    public function CalificacionesRendimientoBajo($idescuela, $idprograma, $idplanestudio = null, $grado = null, $idgrupo = null, $idperiodo, $idestudiante = null, $idmatricula = null) {
        $rendimiento = null;
        $rendimiento = $this->bc->getCalificacionesRendimientoBajo($idescuela, $idprograma, $idplanestudio, $grado, $idgrupo, $idperiodo, $idestudiante, $idmatricula);
        if (isset($rendimiento) && $rendimiento !== null && $rendimiento !== '[]') {
            $pageWidth = $this->getRealPageWidth();
            $rendimiento = json_decode($rendimiento, true);
            if (is_array($rendimiento) && isset($rendimiento[0])) {
                $this->SetFont($this->fontfamilycontent, 'B', 14);
                $this->Cell($pageWidth, 6, 'ESTUDIANTES CON BAJO DESEMPEÑO', null, false, 'C');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', 12);
                $this->Cell($pageWidth / 2, 6, 'PROGRAMA: ' . $rendimiento[0]['nombre_programa'], null, false, 'C');
                $this->Cell($pageWidth / 2, 6, 'AÑO: ' . $idperiodo, null, false, 'C');
                $this->SetFont($this->fontfamilycontent, 'B', 10);
                $this->Ln();
                $i = 0;
                $this->Cell($pageWidth * 8 / 100, 6, 'CÓDIGO', true);
                $this->Cell($pageWidth * 34 / 100, 6, 'NOMBRE DEL ESTUDIANTE', true);
                $this->Cell($pageWidth * 30 / 100, 6, 'ASIGNATURA', true);
                $this->Cell($pageWidth * 4 / 100, 6, 'P1', true, false, 'C');
                $this->Cell($pageWidth * 4 / 100, 6, 'P2', true, false, 'C');
                $this->Cell($pageWidth * 4 / 100, 6, 'P3', true, false, 'C');
                $this->Cell($pageWidth * 4 / 100, 6, 'P4', true, false, 'C');
                $this->Cell($pageWidth * 4 / 100, 6, 'P5', true, false, 'C');
                $this->Cell($pageWidth * 4 / 100, 6, 'P6', true, false, 'C');
                $this->Cell($pageWidth * 4 / 100, 6, 'DEF', true, false, 'C');
                $this->Ln();

                $this->SetFont($this->fontfamilycontent, '', 10);
                $this->SetFillColor(255, 222, 216);
                for ($i = 0; $i < count($rendimiento); $i++) {
                    $fillp1 = false;
                    $fillp2 = false;
                    $fillp3 = false;
                    $fillp4 = false;
                    $fillp5 = false;
                    $fillp6 = false;
                    if (is_numeric($rendimiento[$i]['p1_nd_calificacion']) && $rendimiento[$i]['p1_nd_calificacion'] < $rendimiento[$i]['valaprueba_configuracion']) {
                        $fillp1 = true;
                    }
                    if (is_numeric($rendimiento[$i]['p2_nd_calificacion']) && $rendimiento[$i]['p2_nd_calificacion'] < $rendimiento[$i]['valaprueba_configuracion']) {
                        $fillp2 = true;
                    }
                    if (is_numeric($rendimiento[$i]['p3_nd_calificacion']) && $rendimiento[$i]['p3_nd_calificacion'] < $rendimiento[$i]['valaprueba_configuracion']) {
                        $fillp3 = true;
                    }
                    if (is_numeric($rendimiento[$i]['p4_nd_calificacion']) && $rendimiento[$i]['p4_nd_calificacion'] < $rendimiento[$i]['valaprueba_configuracion']) {
                        $fillp4 = true;
                    }
                    if (is_numeric($rendimiento[$i]['p5_nd_calificacion']) && $rendimiento[$i]['p5_nd_calificacion'] < $rendimiento[$i]['valaprueba_configuracion']) {
                        $fillp5 = true;
                    }
                    if (is_numeric($rendimiento[$i]['p6_nd_calificacion']) && $rendimiento[$i]['p6_nd_calificacion'] < $rendimiento[$i]['valaprueba_configuracion']) {
                        $fillp6 = true;
                    }
                    $this->SetFont($this->fontfamilycontent, '', 10);
                    $this->Cell($pageWidth * 8 / 100, 6, $rendimiento[$i]['id_estudiante'], true);
                    $this->Cell($pageWidth * 34 / 100, 6, $rendimiento[$i]['nombrecompleto_estudiante'], true);
                    $this->Cell($pageWidth * 30 / 100, 6, $rendimiento[$i]['nombre_asignatura'], true);
                    $this->SetFont($this->fontfamilycontent, 'B', 10);
                    $this->Cell($pageWidth * 4 / 100, 6, $rendimiento[$i]['p1_nd_calificacion'], true, false, 'C', $fillp1);
                    $this->Cell($pageWidth * 4 / 100, 6, $rendimiento[$i]['p2_nd_calificacion'], true, false, 'C', $fillp2);
                    $this->Cell($pageWidth * 4 / 100, 6, $rendimiento[$i]['p3_nd_calificacion'], true, false, 'C', $fillp3);
                    $this->Cell($pageWidth * 4 / 100, 6, $rendimiento[$i]['p4_nd_calificacion'], true, false, 'C', $fillp4);
                    $this->Cell($pageWidth * 4 / 100, 6, $rendimiento[$i]['p5_nd_calificacion'], true, false, 'C', $fillp5);
                    $this->Cell($pageWidth * 4 / 100, 6, $rendimiento[$i]['p6_nd_calificacion'], true, false, 'C', $fillp6);
                    $this->Cell($pageWidth * 4 / 100, 6, $rendimiento[$i]['definitiva'], true, false, 'C', false);
                    $this->Ln();
                }
            }
        }
    }

    public function DatosEstadisticosEstudiantes($idescuela, $idprograma = null, $grado = null, $idgrupo = null, $idperiodo = null) {
        $estadisticas = null;
        $estadisticas = $this->bc->getDatosEstadisticosBasicosEstudiantes($idescuela, $idprograma, $grado, $idgrupo, $idperiodo);
        if (isset($estadisticas) && $estadisticas !== null && ($estadisticas['total_hombres'] !== '[]' || $estadisticas['total_mujeres'] !== '[]')) {
            $pageWidth = $this->getRealPageWidth();
            $totalhombres = $estadisticas['total_hombres'];
            $totalmujeres = $estadisticas['total_mujeres'];
            $rangoshombres = $estadisticas['rangos_hombres'];
            $rangosmujeres = $estadisticas['rangos_mujeres'];
            $totalhombres = json_decode($totalhombres, true);
            $totalmujeres = json_decode($totalmujeres, true);
            $rangoshombres = json_decode($rangoshombres, true);
            $rangosmujeres = json_decode($rangosmujeres, true);
            $totalhombres = $totalhombres[0];
            $totalmujeres = $totalmujeres[0];
            $this->SetFont($this->fontfamilycontent, 'B', 14);
            $this->Cell($pageWidth, 10, 'DATOS ESTADISTICOS PARA REPORTAR AL GOBIERNO', null, false, 'C');
            $this->SetFont($this->fontfamilycontent, 'B', 12);
            $this->Ln();
            $this->Cell($pageWidth / 3, 10, 'PROGRAMA: ' . $idprograma, null, false, 'C');
            $this->Cell($pageWidth / 3, 10, 'GRADO: ' . $grado, null, false, 'C');
            $this->Cell($pageWidth / 3, 10, 'GRUPO: ' . $idgrupo, null, false, 'C');
            $this->Ln();
            $htmltable = '';
            $htmltable = $htmltable . '<table border="1">';
            $htmltable = $htmltable . '<tr>';
            $htmltable = $htmltable . '<th><b>Hombres</b></th>';
            $htmltable = $htmltable . '<th><b>Mujeres</b></th>';
            $htmltable = $htmltable . '</tr>';
            $htmltable = $htmltable . '<tr>';

            $htmltable = $htmltable . '<td>';
            $htmltable = $htmltable . 'Total: ' . $totalhombres['Total_Hombres'] . ' Hombres <br>';
            $htmltable = $htmltable . '<table border="0">';
            $htmltable = $htmltable . '<tr>';
            $htmltable = $htmltable . '<th><b>Grado </b></th>';
            $htmltable = $htmltable . '<th><b>Grupo </b></th>';
            $htmltable = $htmltable . '<th><b>Cantidad </b></th>';
            $htmltable = $htmltable . '<th><b>Edad </b></th>';
            $htmltable = $htmltable . '</tr>';
            if (isset($rangoshombres[0])) {
                $i1 = 0;
                for ($i1 = 0; $i1 < count($rangoshombres); $i1++) {
                    $htmltable = $htmltable . '<tr>';
                    $htmltable = $htmltable . '<td>' . $this->getSpanishOrdinalsNumbers($rangoshombres[$i1]['numgrado_programa']) . ' </td>';
                    $htmltable = $htmltable . '<td>' . $rangoshombres[$i1]['id_grupo'] . '</td>';
                    $htmltable = $htmltable . '<td>' . $rangoshombres[$i1]['Cantidad_Hombres'] . '</td>';
                    $htmltable = $htmltable . '<td>' . $rangoshombres[$i1]['Edad_Hombres'] . ' Años </td>';
                    $htmltable = $htmltable . '</tr>';
                }
            }
            $htmltable = $htmltable . '</table>';
            $htmltable = $htmltable . '</td>';

            $htmltable = $htmltable . '<td>';
            $htmltable = $htmltable . 'Total: ' . $totalmujeres['Total_Mujeres'] . ' Mujeres <br>';
            $htmltable = $htmltable . '<table border="0">';
            $htmltable = $htmltable . '<tr>';
            $htmltable = $htmltable . '<th><b>Grado </b></th>';
            $htmltable = $htmltable . '<th><b>Grupo </b></th>';
            $htmltable = $htmltable . '<th><b>Cantidad </b></th>';
            $htmltable = $htmltable . '<th><b>Edad </b></th>';
            $htmltable = $htmltable . '</tr>';
            if (isset($rangosmujeres[0])) {
                $i2 = 0;
                for ($i2 = 0; $i2 < count($rangosmujeres); $i2++) {
                    $htmltable = $htmltable . '<tr>';
                    $htmltable = $htmltable . '<td>' . $this->getSpanishOrdinalsNumbers($rangosmujeres[$i2]['numgrado_programa']) . ' </td>';
                    $htmltable = $htmltable . '<td>' . $rangosmujeres[$i2]['id_grupo'] . '</td>';
                    $htmltable = $htmltable . '<td>' . $rangosmujeres[$i2]['Cantidad_Mujeres'] . '</td>';
                    $htmltable = $htmltable . '<td>' . $rangosmujeres[$i2]['Edad_Mujeres'] . ' Años </td>';
                    $htmltable = $htmltable . '</tr>';
                }
            }
            $htmltable = $htmltable . '</table>';
            $htmltable = $htmltable . '</td>';

            $htmltable = $htmltable . '</tr>';

            $htmltable = $htmltable . '</table>';
            $this->SetFont($this->fontfamilycontent, $this->fontstylecontent, 12);
            $this->writeHTML($htmltable, true, false, false, false, '');
        }
    }

    public function generatePDFDocument() {
        $ahora = getdate();
        $tiempo = $ahora['year'] . $ahora['mon'] . $ahora['mday'] . $ahora['hours'] . $ahora['minutes'] . $ahora['seconds'];
        $this->Output($this->reportname . ' ' . $tiempo . '.pdf', 'I');
    }

}
