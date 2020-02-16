<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$bc = null;
$result = null;
$model = "AsistenciaApp";
$findBy = "id_asistencia";
$rowcount = 0;
$data = null;
$postdata = null;
$count = 0;
$i = 0;
$fecha = date('YmdHis');
$fachai = null;
$fechaf = null;
$result = $session->getSessionStateJSON();
$resultdata = array();
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1)) {
    if (isset($_POST) && $_POST != null) {
        $idprograma = null;
        $idperiodo = null;
        $numgradoprograma = null;
        $idgrupo = null;
        $idperiodonew = null;
        $matriculas = null;
        $configuracion = null;
        $programa = null;
        $rowcount = 0;
        $errormessage = '';

        if (isset($_POST['id_programa']) && isset($_POST['id_periodo']) && isset($_POST['numgrado_programa']) && isset($_POST['id_grupo']) && isset($_POST['id_periodo_new'])) {
            $idescuela = $session->getEnterpriseID();
            $idprograma = $_POST['id_programa'];
            $idperiodo = $_POST['id_periodo'];
            $numgradoprograma = $_POST['numgrado_programa'];
            $idgrupo = $_POST['id_grupo'];
            $idperiodonew = $_POST['id_periodo_new'];
            $bc = new ReportsBank();
            $bc->connect();

            $sql = "SELECT * FROM ConfiguracionApp WHERE id_escuela='" . $session->getEnterpriseID() . "'";
            $configuracion = $bc->selectJSONArray($sql, null);
            $configuracion = json_decode($configuracion, true);
            $configuracion = $configuracion[0];

            $sql = "SELECT * FROM ProgramasApp WHERE id_programa='$idprograma'";
            $programa = $bc->selectJSONArray($sql, null);
            $programa = json_decode($programa, true);
            $programa = $programa[0];
            //echo print_r(json_decode($bc->getPromedioAritmetico(),true));
            $sql = "SELECT M.*, C1.id_matricula AS idmatricula, AVG(C1.pfin_nd_calificacion) AS promedio,"
                    . " (SELECT COUNT(C2.pfin_nd_calificacion) AS cantidadRep FROM CalificacionesApp C2 INNER JOIN ConfiguracionApp Cn ON C2.id_escuela=Cn.id_escuela WHERE C2.id_matricula=C1.id_matricula AND C2.status_calificacion=1 AND C2.pfin_nd_calificacion < Cn.valaprueba_configuracion ) AS cantidad"
                    . " FROM CalificacionesApp C1 INNER JOIN MatriculasApp M ON C1.id_matricula=M.id_matricula WHERE C1.status_calificacion=1 "
                    . " AND C1.id_escuela=:p_id_escuela AND C1.id_periodo=:p_id_periodo AND C1.id_programa=:p_id_programa AND C1.numgrado_programa=:p_numgrado_programa AND C1.id_grupo=:p_id_grupo "
                    . " GROUP BY C1.id_estudiante, C1.id_matricula ORDER BY C1.id_matricula "
                    . "";
            $arraywhere = array();
            $arraywhere['p_id_escuela'] = $idescuela;
            $arraywhere['p_id_programa'] = $idprograma;
            $arraywhere['p_id_periodo'] = $idperiodo;
            $arraywhere['p_numgrado_programa'] = $numgradoprograma;
            $arraywhere['p_id_grupo'] = $idgrupo;
            $matriculas = $bc->selectJSONArray($sql, $arraywhere);
            $bc->setModel('MatriculasApp');

            $arraywhere = array();
            if ($matriculas !== null && is_array(json_decode($matriculas, true)) && $configuracion !== null && is_array($configuracion) && $programa !== null && is_array($programa)) {
                $matriculas = json_decode($matriculas, true);
                for ($i = 0; $i < count($matriculas); $i++) {
                    $bc->setAction('update');
                    $bc->setFindBy('id_matricula');
                    $postdata = array();
                    $postdata['id_matricula'] = $matriculas[$i]['id_matricula'];
                    $postdata['estado_matricula'] = 'Finalizado';
                    $bc->setPostData($postdata);
                    $bc->execute(false);
                    $idmatriculanew = 'M' . $fecha . rand(1, 99) . $matriculas[$i]['id_estudiante'];
                    $postdata = array();
                    $postdata['id_matricula_ant'] = $matriculas[$i]['id_matricula'];
                    $postdata['id_periodo_ant'] = $matriculas[$i]['id_periodo'];
                    $postdata['id_matricula'] = $idmatriculanew;
                    $postdata['id_periodo'] = $idperiodonew;
                    $postdata['id_escuela'] = $matriculas[$i]['id_escuela'];
                    $postdata['id_estudiante'] = $matriculas[$i]['id_estudiante'];
                    $postdata['nombrecompleto_estudiante'] = $matriculas[$i]['nombrecompleto_estudiante'];
                    $postdata['id_escuela'] = $matriculas[$i]['id_escuela'];
                    $postdata['id_sede'] = $matriculas[$i]['id_sede'];
                    $postdata['id_jornada'] = $matriculas[$i]['id_jornada'];
                    $postdata['id_programa'] = $matriculas[$i]['id_programa'];
                    $postdata['id_planestudio'] = $matriculas[$i]['id_planestudio'];
                    $postdata['valor_matricula'] = $programa['valor_programa'];
                    $postdata['estado_matricula'] = 'Normal';
                    $postdata['status_matricula'] = 1;
                    $postdata['fecha_matricula'] = date('Y-m-d');
                    $postdata['usuariocrea_matricula'] = $session->getNickname();
                    $postdata['usuarioedita_matricula'] = $session->getNickname();
                    $postdata['fechacrea_matricula'] = date('Y-m-d H:i:s');
                    $postdata['fechaedita_matricula'] = date('Y-m-d H:i:s');
                    if ($matriculas[$i]['promedio'] >= 1) {
                        if ($matriculas[$i]['promedio'] >= $configuracion['valaprueba_configuracion'] && ($matriculas[$i]['cantidad'] < $configuracion['maxasigrep_configuracion'])) {
                            $numgradoprogramanew = ($matriculas[$i]['numgrado_programa'] + 1);
                            $grados = ($programa['ngrados_programa'] + 0);
                            $gradoviejo = $matriculas[$i]['numgrado_programa'];
                            $grupoviejo = $matriculas[$i]['id_grupo'];
                            $n = 1;
                            $idgruponew = str_replace("" . $gradoviejo, "" . $numgradoprogramanew, "" . $grupoviejo, $n);
                            $grupos = $bc->getGrupos(null, null, null, $idgruponew);
                            if ($numgradoprogramanew <= $grados) {
                                $postdata['numgrado_programa'] = $numgradoprogramanew;
                            }
                            if ($grupos !== null && $grupos !== '[]' && is_array(json_decode($grupos, true)[0])) {
                                $postdata['id_grupo'] = $idgruponew;
                            }
                        } else {
                            $postdata['numgrado_programa'] = $matriculas[$i]['numgrado_programa'];
                            $postdata['id_grupo'] = $matriculas[$i]['id_grupo'];
                        }
                    }
                    $bc->setAction('insert');
                    $bc->setPostData($postdata);
                    $result = $bc->execute(false);
                    if ($bc->getRowCount() > 0) {
                        $rowcount++;
                    } else {
                        if ($bc->getErrorMessage() !== null && $bc->getErrorMessage() !== '') {
                            $errormessage = $errormessage . '' . $nombre . ': ' . $bc->getErrorMessage() . '<br>';
                        }
                    }
                }
            }
            $bc->disconnect();
        }
    }
    $result = json_decode($result, true);
    $result['error'] = $errormessage;
    $result['rowcount'] = $rowcount;
    if ($rowcount >= 1) {
        $result['status'] = 1;
        $result['message'] = 'Se han Finalizado las Promociones Con Exito!.';
        $result['data'] = null;
    } else {
        $result['status'] = 0;
        $result['message'] = 'Hubo un error durante la Operacion!.';
        $result['data'] = null;
    }
    $result = json_encode($result);
    echo $result;
    $result = null;
} else {
    echo $session->getSessionStateJSON();
}
?>