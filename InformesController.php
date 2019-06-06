<?php

ob_start();
include_once './fpdf/fpdf.php';
include_once 'Base/Controllers/XML/DataSettings.php';
include_once 'Base/Controllers/Database/SQLDatabase.php';
include_once 'Base/Controllers/Database/BaseController.php';
include_once 'Base/Controllers/Security/SetsAndHeaders.php';
include_once 'Base/Controllers/Security/SessionManager.php';
include_once 'Base/Controllers/Security/SystemVariableManager.php';
include_once 'Base/Controllers/Reports/FPDFReports.php';
$session = new SessionManager();
$bc = null;
$result = null;
$report = new FPDFReports('Base/Controllers/XML/Settings.xml');
$tipo = null;
if ($session->hasLogin() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1)) {
    if (isset($_POST) && isset($_POST['tipo_informe'])) {
        $tipo = $_POST['tipo_informe'];
        $report->setReportName('Informes Periodicos de Notas');
        $report->setMetaData();
        $report->setPageFormat();
        $report->AliasNbPages();
        $report->setNewFont();
        $report->setNewFontSize();
    }
    if ($tipo !== null && $tipo !== '') {
        if ($tipo == 1) {
            $report->AddPage();
            //$report->ListadoDocentes();
            $report->generateDocument();
        }
    }
} else {
    echo $session->getSessionStateJSON();
}
ob_end_flush();
?>
