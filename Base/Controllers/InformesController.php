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
            $report->InformeCalificaciones();
            $report->generateDocument();
        }
    }
} else {
    echo $session->getSessionStateJSON();
}
ob_end_flush();
?>
