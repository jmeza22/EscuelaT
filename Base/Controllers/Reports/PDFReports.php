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
    public $margintop = null;
    public $marginbottom = null;
    public $marginright = null;
    public $marginleft = null;
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

    public function writeDefaultHeader() {
        if ($this->session->hasLogin()) {
            $configuracion = null;
            $configuracion = $this->bc->getConfiguracionEscuela($this->session->getEnterpriseID());
            if ($configuracion !== null && $configuracion !== '' && $configuracion !== '[]') {
                $configuracion = json_decode($configuracion, true);
            }

            if (is_array($configuracion)) {
                $this->SetFont($this->fontfamilyheader, 'B', 14);
                $this->Cell(0, 8, $configuracion[0]['nombremostrar_configuracion'], 0, 0, 'C');
                $this->Ln();
                $this->Image('../../ImageFiles/' . $configuracion[0]['logo_configuracion'], 3, 3, 20, 20);
                $this->SetFont($this->fontfamilyheader, '', 8);
                $this->MultiCell(0, 6, $configuracion[0]['membrete_configuracion'], 0, 'C');
                $this->SetFont($this->fontfamilyheader, 'I', 8);
                $this->MultiCell(0, 6, $configuracion[0]['eslogan_configuracion'], 0, 'C');
            } else {
                $this->Cell(200, 8, $this->session->getEnterpriseName(), 0, 0, 'C');
            }
            $this->Ln();
        }
    }

    public function writeDefaultFooter() {
        if ($this->session->hasLogin()) {
            $this->SetY(-15);
            $this->SetFont($this->fontfamilyfooter, '', 4);
            $this->Cell(200, 2, 'Sistema Integral de Gestion Academica', 0, 0, 'C');
            $this->Ln();
            $this->SetFont($this->fontfamilyfooter, 'B', 6);
            $this->Cell(200, 2, 'EscuelaT!', 0, 0, 'C');
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

    public function ListadoDocentes() {
        $result = null;
        $result = $this->bc->getDocentes();
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            if (count($result) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', 10);
                $this->Cell(16, 8, 'CODIGO');
                $this->Cell(100, 8, 'NOMBRE COMPLETO DOCENTE');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', 8);
                for ($i = 0; $i < count($result); $i++) {
                    $this->Cell(16, 6, $result[$i]['id_docente']);
                    $this->Cell(100, 6, $result[$i]['nombrecompleto_docente']);
                    $this->Ln();
                }
            }
        }
    }

    public function ListadoEstudiantes() {
        $result = null;
        $result = $this->bc->getEstudiantes();
        if ($result !== null && $result !== '' && $result !== '[]') {
            $result = json_decode($result, true);
            if (count($result) > 0) {
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, 'B', 10);
                $this->Cell(16, 8, 'CODIGO');
                $this->Cell(100, 8, 'NOMBRE COMPLETO');
                $this->Ln();
                $this->SetFont($this->fontfamilycontent, '', 8);
                for ($i = 0; $i < count($result); $i++) {
                    $this->Cell(16, 6, $result[$i]['id_estudiante']);
                    $this->Cell(100, 6, $result[$i]['nombrecompleto_estudiante']);
                    $this->Ln();
                }
            }
        }
    }

    public function InformeCalificaionesTipo1($idescuela = null, $idsede = null, $idprograma = null, $idplanestudio = null, $grado = null, $idgrupo = null, $idperiodo = null, $idcorte = null, $idmatricula = null, $idestudiante = null) {
        $htmltable = '';
        $data = null;
        $subdata = null;
        $data = $this->bc->getInformesCalificaciones($idescuela, $idsede, $idprograma, $idplanestudio, $grado, $idgrupo, $idperiodo, $idmatricula, $idestudiante);
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
                    $htmltable = $htmltable . '<th style="text-align: center; width: 4%;"><b>Au</b></th>';
                    $htmltable = $htmltable . '<th style="text-align: center; width: 46%;"><b>LOGROS / DIFICULTADES / RECOMENDACIONES </b></th>';
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
                            $htmltable = $htmltable . '<td style="text-align: center;">' . utf8_decode($subdata[$j]['horas_asignatura']) . '</td>';
                            $htmltable = $htmltable . '<td style="text-align: center;">' . $subdata[$j]['np1'] . '</td>';
                            $htmltable = $htmltable . '<td style="text-align: center;">' . $subdata[$j]['np2'] . '</td>';
                            $htmltable = $htmltable . '<td style="text-align: center;">' . $subdata[$j]['np3'] . '</td>';
                            $htmltable = $htmltable . '<td style="text-align: center;">' . $subdata[$j]['np4'] . '</td>';
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

                    $this->AddPage();
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
