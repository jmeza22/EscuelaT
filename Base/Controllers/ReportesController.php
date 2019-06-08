<?php

ob_start();
include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$result = null;
$report = new FPDFReports();
$tipo = null;
if ($session->hasLogin() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1)) {
    $report->setMetaData();
    $report->setPageFormat();
    $report->AliasNbPages();
    $report->setNewFont();
    $report->setNewFontSize();
    $report->AddPage();
    if (isset($_POST) && isset($_POST['tipo_reporte'])) {
        $tipo = $_POST['tipo_reporte'];
    }
    if ($tipo !== null && $tipo !== '') {
        if ($tipo === 'ListadoDocentes') {
            $report->setReportName('Listado de Docentes Activos');
            $report->AddPage();
            $report->ListadoDocentes();
            $report->generateDocument();
        }
    }
} else {
    echo $session->getSessionStateJSON();
}
ob_end_flush();
?>
