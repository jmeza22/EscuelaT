<?php

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
$idplanestudio = null;
$idperiodo = null;
$idcorte = null;
$grado = null;
$grupo = null;
$idestudiante = null;
$idmatricula = null;

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
    $idescuela = $idescuela;
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
        if (isset($_POST['id_planestudio']) && $_POST['id_planestudio'] !== '' && $_POST['id_planestudio'] !== 'NULL') {
            $idplanestudio = $_POST['id_planestudio'];
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
        if (isset($_POST['id_matricula']) && $_POST['id_matricula'] !== '' && $_POST['id_matricula'] !== 'NULL') {
            $idmatricula = $_POST['id_matricula'];
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

        if ($tipo === 'CarnetEstudiantil') {
            $report->headertype = -1;
            $report->SetLeftMargin(25);
            $report->SetRightMargin(25);
            $report->AddPage('P', 'Letter', true);
            $report->setReportName('Carnet Estudiantil');
            $report->CarnetsEstudiantiles($idescuela, $idsede, $idjornada, $idprograma, $grado, $grupo, $idperiodo, $idestudiante);
            $report->generatePDFDocument();
        }
        if ($tipo === 'RecibosMatriculas') {
            $report->SetLeftMargin(14);
            $report->SetRightMargin(14);
            $report->AddPage('P', $format, true);
            $report->setReportName('Recibo de Matricula ' . $idprograma . $grado . $grupo . $idperiodo . $idestudiante);
            $report->Matriculas($idescuela, $idsede, $idjornada, $idprograma, $idplanestudio, $grado, $grupo, $idperiodo, $idestudiante, $idmatricula);
            $report->generatePDFDocument();
        }
        if ($tipo === 'CertificadoEstudios') {
            $report->SetLeftMargin(14);
            $report->SetRightMargin(14);
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Certificado de Estudios');
            $report->CertificadoEstudios($idsede, $idjornada, $idprograma, $grado, $grupo, $idperiodo, $idestudiante, null);
            $report->generatePDFDocument();
        }
        if ($tipo === 'CertificadoNotas') {
            $report->SetLeftMargin(12);
            $report->SetRightMargin(12);
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Certificado de Notas');
            $report->CertificadoNotas($idsede, $idjornada, $idprograma, $grado, $grupo, $idperiodo, $idestudiante, null);
            $report->generatePDFDocument();
        }
        if ($tipo === 'EstadisticosBasicosEstudiante') {
            $report->SetLeftMargin(12);
            $report->SetRightMargin(12);
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Datos Estadisticos Basicos de Estudiantes por Grupo ');
            $report->DatosEstadisticosEstudiantes($idescuela, $idprograma, $grado, $grupo, $idperiodo);
            $report->generatePDFDocument();
        }
        if ($tipo === 'PlanillasCalificaciones') {
            $report->SetLeftMargin(12);
            $report->SetRightMargin(12);
            $report->AddPage('L', $format, true);
            $report->setReportName('Planillas Auxiliares de Calificaciones ' . $idprograma . $grupo);
            $report->ListadoPlanillaEstudiantes($idescuela, $idprograma, $idplanestudio, $idperiodo, $idcorte, $grado, $grupo);
            $report->generatePDFDocument();
        }
        if ($tipo === 'Escuelas') {
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Listado de Escuelas');
            $report->ListadoEscuelas();
            $report->generatePDFDocument();
        }
        if ($tipo === 'Sedes') {
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Listado de Sedes');
            $report->ListadoSedes($idescuela);
            $report->generatePDFDocument();
        }
        if ($tipo === 'Programas') {
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Listado de Programas');
            $report->ListadoProgramas($idescuela);
            $report->generatePDFDocument();
        }
        if ($tipo === 'Areas') {
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Listado de Areas');
            $report->ListadoAreas($idescuela);
            $report->generatePDFDocument();
        }
        if ($tipo === 'Asignaturas') {
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Listado de Asignaturas');
            $report->ListadoAsignaturas($idescuela);
            $report->generatePDFDocument();
        }
        if ($tipo === 'PlanEstudioDetallado') {
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Plan de Estudios Detallado');
            $report->ListadoDetalladoPlanEstudio($idescuela, $idprograma, $idplanestudio);
            $report->generatePDFDocument();
        }
        if ($tipo === 'Logros') {
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Listado de Logros');
            $report->ListadoLogrosAsignaturas($idescuela, null, $grado, null);
            $report->generatePDFDocument();
        }
        if ($tipo === 'Grupos') {
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Listado de Grupos');
            $report->ListadoGrupos($idescuela, $idprograma, $grado);
            $report->generatePDFDocument();
        }
        if ($tipo === 'Docentes') {
            $report->AddPage('L', $format, true);
            $report->setReportName('Listado de Docentes');
            $report->ListadoDocentes();
            $report->generatePDFDocument();
        }
        if ($tipo === 'ContactosDocentes') {
            $report->AddPage('L', $format, true);
            $report->setReportName('Datos de Contacto Docentes');
            $report->ListadoContactosDocentes();
            $report->generatePDFDocument();
        }
        if ($tipo === 'CargasDocentes') {
            $report->AddPage('L', $format, true);
            $report->setReportName('Carga Academica Docente');
            $report->ListadoCargasDocentes($idescuela, $idperiodo, $idprograma, null, $grupo);
            $report->generatePDFDocument();
        }
        if ($tipo === 'DirectoresGrupos') {
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Listado de Directores de Grupos');
            $report->ListadoDirectoresGrupos($idescuela, $grupo, $idperiodo);
            $report->generatePDFDocument();
        }
        if ($tipo === 'Estudiantes') {
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Listado de Estudiantes');
            $report->ListadoEstudiantes($idescuela, $idsede, $idjornada, $idprograma, $grado, $grupo, $idperiodo);
            $report->generatePDFDocument();
        }
        if ($tipo === 'ContactosEstudiantes') {
            $report->AddPage('L', $format, true);
            $report->setReportName('Datos de Contacto de Estudiantes');
            $report->ListadoContactosEstudiantes($idescuela, $idprograma, $idperiodo, $grado, $grupo, $idestudiante);
            $report->generatePDFDocument();
        }
        if ($tipo === 'UsuariosDocentes') {
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Listado de Usuarios de Docentes');
            $report->ListadoUsuarios($idescuela, 'Teacher');
            $report->generatePDFDocument();
        }
        if ($tipo === 'UsuariosEstudiantes') {
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Listado de Usuarios de Estudiantes');
            $report->ListadoUsuarios($idescuela, 'Student');
            $report->generatePDFDocument();
        }
        if ($tipo === 'UsuariosAcudientes') {
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Listado de Usuarios de Acudientes');
            $report->ListadoUsuarios($idescuela, 'Visitor');
            $report->generatePDFDocument();
        }
        if ($tipo === 'ObservadorEstudiante') {
            $report->AddPage($orientation, $format, true);
            $report->setReportName('Observador del Estudiante ' . $idestudiante);
            $report->ObservadorEstudiante($idestudiante);
            $report->generatePDFDocument();
        }
        if ($tipo === 'RendimientoBajo') {
            $report->AddPage('L', $format, true);
            $report->setReportName('Estudiantes con Bajo Rendimiento');
            $report->CalificacionesRendimientoBajo($idescuela, $idprograma, $idplanestudio, $grado, $grupo, $idperiodo, $idestudiante, null);
            $report->generatePDFDocument();
        }
    }
} else {
    echo $session->getSessionStateJSON();
}
?>
