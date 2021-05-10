<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
include_once 'Classes/Personas.php';
include_once 'Classes/Matriculas.php';
include_once 'Classes/MatriculaAsignaturas.php';
$session = new SessionManager();
$bc = null;
$result = null;
$modelP = "PersonasApp";
$modelE = "ObservadorEstudianteApp";
$modelM = "MatriculasApp";
$findByP = "id_persona";
$findByE = "id_estudiante";
$findByM = "id_matricula";
$rowcount = 0;
$data = null;
$postdata = null;
$count = 0;
$i = 0;
$msgnumeditados = '';
$msgnummat = '';
$errormessage = '';
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1)) {
    if (isset($_POST['id_grupo']) && $_POST['id_grupo'] != null) {
        $idsede = $_POST['id_sede'];
        unset($_POST['id_sede']);
        $idjornada = $_POST['id_jornada'];
        unset($_POST['id_jornada']);
        $idprograma = $_POST['id_programa'];
        unset($_POST['id_programa']);
        $idplanestudio = $_POST['id_planestudio'];
        unset($_POST['id_planestudio']);
        $idperiodo = $_POST['id_periodo'];
        unset($_POST['id_periodo']);
        $numgrado = $_POST['numgrado_programa'];
        unset($_POST['numgrado_programa']);
        $idgrupo = $_POST['id_grupo'];
        unset($_POST['id_grupo']);
        unset($_POST['estado_matricula']);
        unset($_POST['tipo_reporte']);

        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($modelP);
        $bc->setFindBy($findByP);
        $bc->setAction('insertorupdate');

        if (isset($_POST[$findByE])) {

            $data = array();
            $postdata = $bc->getPostData();
            $count = count($postdata[$findByE]);
            if (is_array($postdata[$findByE]) && $count > 0) {
                $postdata = $bc->parseMultiRows($postdata);
                $count = count($postdata);
                for ($i = 0; $i < $count; $i++) {
                    
                    //pasar el nombre a mayusculas
                    $postdata[$i]['apellido1_persona']= strtoupper($postdata[$i]['apellido1_persona']);
                    $postdata[$i]['apellido2_persona']= strtoupper($postdata[$i]['apellido2_persona']);
                    $postdata[$i]['nombre1_persona']= strtoupper($postdata[$i]['nombre1_persona']);
                    $postdata[$i]['nombre2_persona']= strtoupper($postdata[$i]['nombre2_persona']);
                    
                    $arraywhere = array();
                    $arraywhere['p_nombre1'] = $postdata[$i]['nombre1_persona'];
                    $arraywhere['p_nombre2'] = $postdata[$i]['nombre2_persona'];
                    $arraywhere['p_apellido1'] = $postdata[$i]['apellido1_persona'];
                    $arraywhere['p_apellido2'] = $postdata[$i]['apellido2_persona'];

                    $sql = "SELECT * FROM PersonasApp WHERE "
                            . " nombre1_persona LIKE :p_nombre1 "
                            . " AND nombre2_persona LIKE :p_nombre2 "
                            . " AND apellido1_persona LIKE :p_apellido1 "
                            . " AND apellido2_persona LIKE :p_apellido2 ";
                    $resultP0 = null;
                    $resultP0 = $bc->selectAssocArray($sql, $arraywhere); // buscar un homonimo

                    $persona = new Personas();

                    if (!isset($resultP0[0])) { // no hay homonimo
                        $per = $postdata[$i];
                        $per['id_persona'] = 0;
                        unset($per['id_estudiante']);
                        $persona->setArray($per);
                        $persona->beginTransaction();
                        $resultPer = $persona->insertPersona();
                        if ($persona->getRowCount() > 0) {
                            $persona->insertUsuario();
                            $persona->insertEstudiante();
                            if ($persona->getRowCount() > 0) {
                                $postdata[$i]['id_estudiante'] = $persona->getIdPersona();
                                $persona->commit();
                            } else {
                                $persona->rollback();
                            }
                        }
                    } else { // si hay homonimo 
                        $postdata[$i]['id_estudiante'] = $resultP0[0]['id_persona'];
                    }

                    if (isset($postdata[$i]['id_estudiante']) && $postdata[$i]['id_estudiante'] !== 0 && $postdata[$i]['id_estudiante'] !== '') { // si ya está matriculado se puede editar datos basicos
                        $P = array();
                        $P = $postdata[$i];
                        $P['id_persona'] = $P['id_estudiante'];
                        unset($P['id_estudiante']);
                        unset($P['id_matricula']);
                        $persona->setArray($P);
                        $persona->setData();
                        $persona->updatePersona();
                        if ($persona->getRowCount() > 0) {
                            $msgnumeditados = $msgnumeditados . $P['nombre1_persona'] . ' ' . $P['nombre1_persona'] . ' / ';
                        }
                        $result = $persona->insertEstudiante();
                    }

                    if ($postdata[$i]['id_estudiante'] !== 0 && $postdata[$i]['id_estudiante'] !== '') {
                        $postdata[$i]['nombrecompleto_estudiante'] = $postdata[$i]['apellido1_persona'] . ' ' . $postdata[$i]['apellido2_persona'] . ' ' . $postdata[$i]['nombre1_persona'] . ' ' . $postdata[$i]['nombre2_persona'];
                        unset($postdata[$i]['apellido1_persona']);
                        unset($postdata[$i]['apellido2_persona']);
                        unset($postdata[$i]['nombre1_persona']);
                        unset($postdata[$i]['nombre2_persona']);
                        $M = array();
                        $M['id_estudiante'] = $postdata[$i]['id_estudiante'];
                        $M['nombrecompleto_estudiante'] = $postdata[$i]['nombrecompleto_estudiante'];
                        $M['id_escuela'] = $session->getEnterpriseID();
                        $M['id_sede'] = $idsede;
                        $M['id_jornada'] = $idjornada;
                        $M['id_programa'] = $idprograma;
                        $M['id_planestudio'] = $idplanestudio;
                        $M['id_periodo'] = $idperiodo;
                        $M['numgrado_programa'] = $numgrado;
                        $M['id_grupo'] = $idgrupo;
                        $M['id_matricula'] = 'M' . $M['id_escuela'] . $M['id_periodo'] . $M['id_programa'] . $M['id_estudiante'];
                        $M['status_matricula'] = 1;
                        $matriculas = new Matriculas();
                        $matriculas->setArray($M);
                        $matriculas->setData();
                        $result = $matriculas->insertMatricula();

                        if ($matriculas->getRowCount() > 0) {
                            $M['id_matasig'] = $M['id_matricula'];
                            $MatAsig = new MatriculaAsignaturas();
                            $MatAsig->setArray($M);
                            $MatAsig->setAction('insertorupdate');
                            $MatAsig->insertAuto();
                            $rowcount++;
                        } else {
                            $error = $matriculas->getErrorMessage();
                            if ($error !== null && $error !== '') {
                                $errormessage = $errormessage . '  {{' . $M['nombrecompleto_estudiante'] . ':' . $error . ' }} ';
                            }
                        }
                    }
                }
            }
        }
        $result = json_decode($result, true);
        $result['error'] = $errormessage;
        $result['message'] = "";
        $result['status'] = 0;
        if ($rowcount > 0) {
            $result['status'] = 1;
            $result['message'] = "Bien hecho!. Se matricularon $rowcount estudiantes en el grupo $idgrupo. ";
        }
        if ($msgnumeditados !== '') {
            $result['status'] = 1;
            $result['message'] = $result['message'] . "Se han actualizado los siguientes estudiante $msgnumeditados.";
        }
        if ($result['message'] === '') {
            $result['message'] = "No se realizaron matriculas ni cambios en los registros.";
        }
        $result = json_encode($result);
        echo $result;
        $bc->disconnect();
    }
}
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>