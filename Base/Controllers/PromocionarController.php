<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$bc = null;
$result = null;
$model = "MatriculasApp";
$findBy = "id_matricula";
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
    if (isset($_POST['id_programa']) && $_POST['id_programa'] != null) {
        $idprograma = null;
        $idperiodo = null;
        $numgrado = null;
        $idgrupo = null;
        $idperiodonew = null;
        $numgradonew = null;
        $idgruponew = null;
        $matriculas = null;
        $configuracion = null;
        $programa = null;
        $rowcount = 0;
        $errormessage = '';

        if (isset($_POST['id_programa']) && isset($_POST['id_periodo']) && isset($_POST['numgrado_programa']) && isset($_POST['id_grupo']) && isset($_POST['id_periodo_new'])) {
            $idescuela = $session->getEnterpriseID();
            $idprograma = $_POST['id_programa'];
            $idperiodo = $_POST['id_periodo'];
            $numgrado = null;
            $idgrupo = null;
            if ($_POST['numgrado_programa'] != "" && $_POST['numgrado_programa'] !== "NULL") {
                $numgrado = $_POST['numgrado_programa'];
            }
            if ($_POST['id_grupo'] !== "" && $_POST['id_grupo'] !== "NULL") {
                $idgrupo = $_POST['id_grupo'];
            }
            $idperiodonew = $_POST['id_periodo_new'];
            $numgradonew = $_POST['numgrado_programa_new'];
            $idgruponew = $_POST['id_grupo_new'];
            $bc = new ReportsBank();
            $bc->connect();

            $configuracion = $bc->getConfiguracionEscuela($idescuela);
            $configuracion = json_decode($configuracion, true);
            $configuracion = $configuracion[0];

            $programa = $bc->getProgramas($idescuela, $idprograma);
            $programa = json_decode($programa, true);
            $programa = $programa[0];
            $matriculas = null;
            $matriculas = $bc->getPromedio($idescuela, $idprograma, null, $numgrado, $idgrupo, $idperiodo, null, null);

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
                    $nombre = $matriculas[$i]['nombrecompleto_estudiante'];

                    $cantReprobadas = $bc->getCantidadAsignaturasReprobadas($matriculas[$i]['id_escuela'], $matriculas[$i]['id_programa'], $matriculas[$i]['id_planestudio'], $matriculas[$i]['numgrado_programa'], $matriculas[$i]['id_grupo'], $matriculas[$i]['id_periodo'], $matriculas[$i]['id_estudiante'], $matriculas[$i]['id_matricula']);
                    if ($cantReprobadas !== null && $cantReprobadas !== '[]') {
                        $cantReprobadas = json_decode($cantReprobadas, true);
                        if (is_array($cantReprobadas) && isset($cantReprobadas[0])) {
                            $cantReprobadas = $cantReprobadas[0]['CantidadReprobadas'];
                        }
                    }
                    if ($matriculas[$i]['Promedio'] >= 1) {
                        if (($matriculas[$i]['Promedio'] >= $configuracion['valaprueba_configuracion']) && ($cantReprobadas < $configuracion['maxasigrep_configuracion'])) {
                            $numgradonew = ($matriculas[$i]['numgrado_programa'] + 1);
                            $grados = ($programa['ngrados_programa'] + 0);
                            if ($numgradonew <= $grados) {
                                $postdata['numgrado_programa'] = $numgradonew;
                            }
                            $grupoAnt = $bc->getGrupos($matriculas[$i]['id_escuela'], $matriculas[$i]['id_programa'], $matriculas[$i]['numgrado_programa'], $matriculas[$i]['id_grupo']);
                            if ($grupoAnt !== null && $grupoAnt !== '[]') {
                                $grupoAnt = json_decode($grupoAnt, true);
                                $grupoAnt = $grupoAnt[0];
                            }
                            if (is_array($grupoAnt)) {
                                $grupoNuevo = $bc->getGrupos($grupoAnt['id_escuela'], $grupoAnt['id_programa'], $postdata['numgrado_programa'], null, $grupoAnt['num_grupo']);
                                if ($grupoNuevo !== null && $grupoNuevo !== '[]') {
                                    $grupoNuevo = json_decode($grupoNuevo, true);
                                    $grupoNuevo = $grupoNuevo[0];
                                    $postdata['id_grupo'] = $grupoNuevo['id_grupo'];
                                }
                            }
                            if ($idgruponew !== null && $idgruponew !== '' && $idgruponew !== 'NULL') {
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
}
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>