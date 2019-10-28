<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$bc = null;
$result = null;
$model = "CalificacionesApp";
$findBy = 'id_calificacion';
$rowcount = 0;
$nullrowcount = 0;
$data = null;
$postdata = null;
$resultdata = null;
$count = 0;
$i = 0;
$idperiodo = null;
$idcorte = null;
$numcorte = null;
$estadocorte = null;
$nombre = '';
$errormessage = '';
$fecha = date('Y-m-d H:i:s');
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getStandard() == 1)) {
    if (isset($_POST) && $_POST != null) {
        $bc = new BaseController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction('insertorupdate');
        $idperiodo = $variables->getIdPeriodoAnual();
        $idcorte = $variables->getIdCortePeriodo();
        $numcorte = $variables->getNumCortePeriodo();
        $estadocorte = $variables->getEstadoCortePeriodo();
        if (isset($_POST[$findBy])) {

            $data = array();
            $postdata = $bc->getPostData();
            $count = count($_POST[$findBy]);

            if ($count >= 1) {
                $postdata = $bc->parseMultiRows($postdata);
                $count = count($postdata);
                for ($i = 0; $i < $count; $i++) {
                    $data = $postdata[$i];
                    if ($data[$findBy] === '{{' . $findBy . '}}') {
                        unset($postdata[$i]);
                    }
                }
                $data = null;
                $resultdata=$postdata;
                for ($i = 0; $i < $count; $i++) {
                    if (isset($postdata[$i])) {
                        $data = $postdata[$i];
                        $nombre = $data["nombrecompleto_estudiante"];
                        unset($data["nombrecompleto_estudiante"]);
                        $data["id_escuela"] = $session->getEnterpriseID();
                        $data["p" . $numcorte . "_id_docente"] = $session->getUserID();
                        unset($data["id_docente"]);
                        $data["p" . $numcorte . "_nc_calificacion"] = $data["nc_calificacion"];
                        unset($data["nc_calificacion"]);
                        $data["p" . $numcorte . "_np_calificacion"] = $data["np_calificacion"];
                        unset($data["np_calificacion"]);
                        $data["p" . $numcorte . "_na_calificacion"] = $data["na_calificacion"];
                        unset($data["na_calificacion"]);
                        $data["p" . $numcorte . "_nd_calificacion"] = $data["nd_calificacion"];
                        unset($data["nd_calificacion"]);
                        //$data["p" . $numcorte . "_nn_calificacion"] = $data["nn_calificacion"];
                        unset($data["nn_calificacion"]);
                        $data["p" . $numcorte . "_logroc_calificacion"] = $data["logroc_calificacion"];
                        unset($data["logroc_calificacion"]);
                        $data["p" . $numcorte . "_logrop_calificacion"] = $data["logrop_calificacion"];
                        unset($data["logrop_calificacion"]);
                        $data["p" . $numcorte . "_logroa_calificacion"] = $data["logroa_calificacion"];
                        unset($data["logroa_calificacion"]);
                        $data["p" . $numcorte . "_ausencias_calificacion"] = $data["ausencias_calificacion"];
                        unset($data["ausencias_calificacion"]);
                        $data["p" . $numcorte . "_id_corte"] = $data["id_corte"];
                        unset($data["id_corte"]);
                        if($data[$findBy]===0){
                            $data["usuariocrea_calificacion"]=$session->getNickname();
                            $data["fechacrea_calificacion"]=$fecha;
                        }
                        if($data[$findBy]!==0){
                            $data["usuarioedita_calificacion"]=$session->getNickname();
                            $data["fechaedita_calificacion"]=$fecha;
                        }
                        if (isset($data[$findBy]) && $data[$findBy] !== null && $data[$findBy] !== '{{' . $findBy . '}}') {
                            if ($estadocorte === 'A') {
                                $bc->setPostData($data);
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
                    }
                }
            }
        }
        
        unset($resultdata['action']);
        unset($resultdata['findby']);
        unset($resultdata['model']);
        unset($resultdata['token']);
        
        $result = json_decode($result, true);
        $result['error'] = $errormessage;
        if ($estadocorte !== 'A') {
            $result['status'] = 0;
            $result['message'] = 'El periodo seleccionado se encuentra inactivo!.';
        }
        if ($rowcount >= 1) {
            $result['status'] = 1;
            $result['message'] = 'Informacion Almacenada!.';
            $result['data']= json_encode($resultdata);
            //$result['data']= json_encode($_POST);
        }
        $result = json_encode($result);
        echo $result;
        $result = null;
        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}

?>