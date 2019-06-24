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
//print_r($_POST);
if ($session->hasLogin() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1)) {
    $idescuela = $session->getEnterpriseID();
    if (isset($_POST) && isset($_POST['tipo_reporte'])) {
        $tipo = $_POST['tipo_reporte'];
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
    }
    if (isset($_POST) && isset($_POST['format_page'])) {
        $format = $_POST['format_page'];
        if (isset($_POST) && isset($_POST['orientation_page']) && $_POST['orientation_page'] !== '' && $_POST['orientation_page'] !== 'NULL') {
            $orientation = $_POST['orientation_page'];
        }
        if (isset($_POST) && isset($_POST['left_margin']) && $_POST['left_margin'] !== '' && $_POST['left_margin'] !== 'NULL') {
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
    }

    if ($tipo !== null && $tipo !== '') {
        $report->setMetaData();
        $report->setPageOrientation($orientation);
        $report->SetTopMargin($top + 22);
        $report->SetLeftMargin($left);
        $report->SetRightMargin($right);
        $report->SetAutoPageBreak(true, $bottom);
        $report->setFontHeader($family, '', $sizeheader);
        $report->setFontContent($family, '', $sizecontent);
        $report->setFontFooter($family, 'I', $sizefooter);
        $report->SetFont($family, $style, $sizecontent);
        $report->AddPage($orientation, $format, true);

        if ($tipo === 'CertificadoEstudios') {
            $report->SetLeftMargin(14);
            $report->SetRightMargin(14);
            $report->setReportName('Certificado de Estudios');
            $report->CertificadoEstudios($idsede, $idjornada, $idprograma, $grado, $grupo, $idperiodo, $idestudiante, null);
            $report->generatePDFDocument();
        }
        if ($tipo === 'CertificadoNotas') {
            $report->SetLeftMargin(12);
            $report->SetRightMargin(12);
            $report->setReportName('Certificado de Notas');
            $report->CertificadoNotas($idsede, $idjornada, $idprograma, $grado, $grupo, $idperiodo, $idestudiante, null);
            $report->generatePDFDocument();
        }
        if ($tipo === 'Escuelas') {
            $report->setReportName('Listado de Escuelas');
            $report->ListadoEscuelas();
            $report->generatePDFDocument();
        }
        if ($tipo === 'Sedes') {
            $report->setReportName('Listado de Sedes');
            $report->ListadoSedes($session->getEnterpriseID());
            $report->generatePDFDocument();
        }
        if ($tipo === 'Programas') {
            $report->setReportName('Listado de Programas');
            $report->ListadoProgramas($session->getEnterpriseID());
            $report->generatePDFDocument();
        }
        if ($tipo === 'Areas') {
            $report->setReportName('Listado de Areas');
            $report->ListadoAreas($session->getEnterpriseID());
            $report->generatePDFDocument();
        }
        if ($tipo === 'Asignaturas') {
            $report->setReportName('Listado de Asignaturas');
            $report->ListadoAsignaturas($session->getEnterpriseID());
            $report->generatePDFDocument();
        }
        if ($tipo === 'Logros') {
            $report->setReportName('Listado de Logros');
            $report->ListadoLogrosAsignaturas($idescuela, null, $grado, null);
            $report->generatePDFDocument();
        }
        if ($tipo === 'Grupos') {
            $report->setReportName('Listado de Grupos');
            $report->ListadoGrupos($session->getEnterpriseID(), $idprograma, $grado);
            $report->generatePDFDocument();
        }
        if ($tipo === 'Docentes') {
            $report->setReportName('Listado de Docentes');
            $report->ListadoDocentes();
            $report->generatePDFDocument();
        }
        if ($tipo === 'CargasDocentes') {
            $report->setReportName('Carga Academica Docente');
            $report->ListadoCargasDocentes($session->getEnterpriseID(), $idperiodo, $idprograma, null, $grupo);
            $report->generatePDFDocument();
        }
        if ($tipo === 'DirectoresGrupos') {
            $report->setReportName('Listado de Directores de Grupos');
            $report->ListadoDirectoresGrupos($session->getEnterpriseID(), $grupo, $idperiodo);
            $report->generatePDFDocument();
        }
        if ($tipo === 'Estudiantes') {
            $report->setReportName('Listado de Estudiantes');
            $report->ListadoEstudiantes($idescuela, $idsede, $idjornada, $idprograma, $grado, $grupo, $idperiodo);
            $report->generatePDFDocument();
        }
        if ($tipo === 'UsuariosDocentes') {
            $report->setReportName('Listado de Usuarios de Docentes');
            $report->ListadoUsuarios($session->getEnterpriseID(), 'Teacher');
            $report->generatePDFDocument();
        }
        if ($tipo === 'UsuariosEstudiantes') {
            $report->setReportName('Listado de Usuarios de Estudiantes');
            $report->ListadoUsuarios($session->getEnterpriseID(), 'Student');
            $report->generatePDFDocument();
        }
        if ($tipo === 'UsuariosAcudientes') {
            $report->setReportName('Listado de Usuarios de Acudientes');
            $report->ListadoUsuarios($session->getEnterpriseID(), 'Visitor');
            $report->generatePDFDocument();
        }
    }
} else {
    echo $session->getSessionStateJSON();
}
ob_end_flush();
?>
