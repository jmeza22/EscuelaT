<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Reports
 *
 * @author LISANDRO
 */
class FPDFReports extends FPDF {

    public $bc = null;
    public $session = null;
    public $reportname = 'Reporte';
    public $pagesize = null;
    public $pageorientation = null;
    public $margintop = null;
    public $marginbottom = null;
    public $marginright = null;
    public $marginleft = null;
    public $fontfamily = null;
    public $fontsize = null;
    public $fontstyle = null;
    public $headertype = 0;
    public $footertype = 0;

    public function __construct($urlsettingsdb = null) {
        parent::__construct();
        $this->bc = new BancoReportes($urlsettingsdb);
        $this->session = new SessionManager();
    }

    public function setReportName($name = 'Reporte') {
        $this->reportname = $name;
    }

    public function setMetaData() {
        $this->SetAuthor('JOSE MEZA');
        $this->SetCreator('EscuelaT!');
        $this->SetSubject($this->reportname);
        $this->SetKeywords('EscuelaT!');
        $this->SetTitle('Reporte: '.$this->reportname);
    }

    public function setPageFormat($pagesize = 'Legal', $orientation = 'P') {
        $this->pagesize = $pagesize;
        $this->pageorientation = $orientation;
    }

    public function setNewMargins($top = 3, $left = 2, $right = 2) {
        $this->margintop = $top;
        $this->marginleft = $left;
        $this->marginright = $right;
        $this->SetMargins($this->marginleft, $this->margintop, $this->marginright);
    }

    public function setNewFont($fontfamily = 'Arial', $style = '') {
        $this->fontfamily = $fontfamily;
        $this->fontstyle = $style;
        $this->SetFont($this->fontfamily, $this->fontstyle);
    }

    public function setNewFontSize($fontsize = 12) {
        $this->fontsize = $fontsize;
        $this->SetFontSize($this->fontsize);
    }

    public function writeDefaultHeader() {
        if ($this->session->hasLogin()) {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(200, 8, $this->session->getEnterpriseName(), 0, 0, 'C');
            $this->Ln();
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(200, 4, $this->reportname, 0, 0, 'C');
        }
    }

    public function writeDefaultFooter() {
        if ($this->session->hasLogin()) {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 6, 'Pagina: ' . $this->PageNo(), 0, 0, 'L');
            $this->Cell(0, 6, 'Generado por: ' . $this->session->getNickname(), 0, 0, 'R');
            $this->Ln();
            $this->SetFont('Arial', '', 6);
            $this->Cell(200, 2, 'Sistema Integral de Gestion Academica', 0, 0, 'C');
            $this->Ln();
            $this->SetFont('Arial', 'B', 6);
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
                $this->SetFont('Arial', 'B', 10);
                $this->Cell(16, 8, 'CODIGO');
                $this->Cell(100, 8, 'NOMBRE COMPLETO DOCENTE');
                $this->Ln();
                $this->SetFont('Arial', '', 8);
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
                $this->SetFont('Arial', 'B', 10);
                $this->Cell(16, 8, 'CODIGO');
                $this->Cell(100, 8, 'NOMBRE COMPLETO');
                $this->Ln();
                $this->SetFont('Arial', '', 8);
                for ($i = 0; $i < count($result); $i++) {
                    $this->Cell(16, 6, $result[$i]['id_estudiante']);
                    $this->Cell(100, 6, $result[$i]['nombrecompleto_estudiante']);
                    $this->Ln();
                }
            }
        }
    }

    public function generateDocument() {
        $ahora = getdate();
        $tiempo = $ahora['year'] . $ahora['mon'] . $ahora['mday'] . $ahora['hours'] . $ahora['minutes'] . $ahora['seconds'];
        $this->Output('I', $this->reportname . ' ' . $tiempo . '.pdf', true);
    }

}
