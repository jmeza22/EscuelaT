<?php

ob_start();
include_once './fpdf/fpdf.php';
include_once 'Base/Controllers/XML/DataSettings.php';
include_once 'Base/Controllers/Database/SQLDatabase.php';
include_once 'Base/Controllers/Database/BaseController.php';
include_once 'Base/Controllers/Security/SetsAndHeaders.php';
include_once 'Base/Controllers/Security/SessionManager.php';
include_once 'Base/Controllers/Security/SystemVariableManager.php';
include_once 'Base/Controllers/Reports/BancoReportes.php';
include_once 'Base/Controllers/Reports/FPDFReports.php';
$session = new SessionManager();
$bc = null;
$result = null;
$report = new FPDFReports('Base/Controllers/XML/Settings.xml');
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
