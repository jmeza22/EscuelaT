<?php

ob_start();
include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$result = null;
$report = new PDFReports();
$tipo = null;
$idescuela = null;
$idsede = null;
$idjornada = null;
$idprograma = null;
$idperiodo = null;
$idcorte = null;
$grado = null;
$grupo = null;
$idestudiante = null;

$format = 'Legal';
$orientation = 'P';
$left = 1;
$right = 1;
$top = 1;
$bottom = 1;

$family = 'courier';
$style = '';
$sizecontent = 10;
$sizeheader = 8;
$sizefooter = 6;

if ($session->hasLogin() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1)) {
    $idescuela = $session->getEnterpriseID();
    if (isset($_POST) && isset($_POST['tipo_informe'])) {
        $tipo = $_POST['tipo_informe'];
        if (isset($_POST['id_sede']) && $_POST['id_sede'] !== '' && $_POST['id_sede'] !== 'NULL') {
            $idsede = $_POST['id_sede'];
        }
        if (isset($_POST['id_jornada']) && $_POST['id_jornada'] !== '' && $_POST['id_jornada'] !== 'NULL') {
            $idjornada = $_POST['id_jornada'];
        }
        if (isset($_POST['id_programa']) && $_POST['id_programa'] !== '' && $_POST['id_programa'] !== 'NULL') {
            $idprograma = $_POST['id_programa'];
        }
        if (isset($_POST['id_periodo']) && $_POST['id_periodo'] !== '' && $_POST['id_periodo'] !== 'NULL') {
            $idperiodo = $_POST['id_periodo'];
        }
        if (isset($_POST['id_corte']) && $_POST['id_corte'] !== '' && $_POST['id_corte'] !== 'NULL') {
            $idcorte = $_POST['id_corte'];
        }
        if (isset($_POST['numgrado_programa']) && $_POST['numgrado_programa'] !== '' && $_POST['numgrado_programa'] !== 'NULL') {
            $grado = $_POST['numgrado_programa'];
        }
        if (isset($_POST['id_grupo']) && $_POST['id_grupo'] !== '' && $_POST['id_grupo'] !== 'NULL') {
            $grupo = $_POST['id_grupo'];
        }
        if (isset($_POST['id_estudiante']) && $_POST['id_estudiante'] !== '' && $_POST['id_estudiante'] !== 'NULL') {
            $idestudiante = $_POST['id_estudiante'];
        }
        $report->setReportName('Informe de Calificaciones. ' . $idprograma . '_' . $idcorte . '_' . $grupo);
        $report->setMetaData();
    }
    if (isset($_POST) && isset($_POST['format_page'])) {
        $format = $_POST['format_page'];
        if (isset($_POST) && isset($_POST['orientation_page']) && $_POST['orientation_page'] !== '' && $_POST['orientation_page'] !== 'NULL') {
            $orientation = $_POST['orientation_page'];
        }
        if (isset($_POST) && isset($_POST['left_margin'])  && $_POST['left_margin'] !== '' && $_POST['left_margin'] !== 'NULL') {
            $left = $_POST['left_margin'];
        }
        if (isset($_POST) && isset($_POST['right_margin']) && $_POST['right_margin'] !== '' && $_POST['right_margin'] !== 'NULL') {
            $right = $_POST['right_margin'];
        }
        if (isset($_POST) && isset($_POST['top_margin']) && $_POST['top_margin'] !== '' && $_POST['top_margin'] !== 'NULL') {
            $top = $_POST['top_margin'];
        }
        if (isset($_POST) && isset($_POST['bottom_margin']) && $_POST['bottom_margin'] !== '' && $_POST['bottom_margin'] !== 'NULL') {
            $bottom = $_POST['bottom_margin'];
        }
        if (isset($_POST) && isset($_POST['family_font']) && $_POST['family_font'] !== '' && $_POST['family_font'] !== 'NULL') {
            $family = $_POST['family_font'];
        }
        if (isset($_POST) && isset($_POST['sizeheader_font']) && $_POST['sizeheader_font'] !== '' && $_POST['sizeheader_font'] !== 'NULL') {
            $sizeheader = $_POST['sizeheader_font'];
        }
        if (isset($_POST) && isset($_POST['sizecontent_font']) && $_POST['sizecontent_font'] !== '' && $_POST['sizecontent_font'] !== 'NULL') {
            $sizecontent = $_POST['sizecontent_font'];
        }
        if (isset($_POST) && isset($_POST['sizefooter_font']) && $_POST['sizefooter_font'] !== '' && $_POST['sizefooter_font'] !== 'NULL') {
            $sizefooter = $_POST['sizefooter_font'];
        }
        $report->setPageOrientation($orientation);
        $report->SetTopMargin($top+18);
        $report->SetLeftMargin($left);
        $report->SetRightMargin($right);
        $report->SetAutoPageBreak(true, $bottom);
        $report->setFontHeader($family, '', $sizeheader);
        $report->setFontContent($family, '', $sizecontent);
        $report->setFontFooter($family, 'I', $sizefooter);
        $report->SetFont($family, $style, $sizecontent);
        $report->AddPage($orientation, $format, true);
            
    }

    if ($tipo !== null && $tipo !== '') {
        if ($tipo == 1) {
            $report->InformeCalificaionesTipo1($idescuela, $idsede, $idprograma, null, $grado, $grupo, $idperiodo, $idcorte, null, $idestudiante);
            $report->generatePDFDocument();
        }
    }
} else {
    echo $session->getSessionStateJSON();
}
ob_end_flush();
?>
