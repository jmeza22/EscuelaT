<?php

include_once 'Libraries/Controllers.php';
include_once 'Others/UploadDocument.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'SolucionesActividadesVirtualesApp';
$findBy = 'id_solucion';
$action = 'insertorupdate';
$postdata = null;
$fecha = date('Y-m-d H:i:s');
if ($session->hasLogin() && $session->checkToken() && ($session->getUserType() === 'Teacher' || $session->getUserType() === 'Student')) {
    if (isset($_POST['action']) && isset($_POST['id_solucion']) && isset($_POST['id_actividad']) && isset($_POST['id_estudiante'])) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        $upload = new UploadDocument();
        $upload->setURL('../../DocumentFiles/');
        $upload->setFileName('document-file');

        if (isset($_FILES['document-file']) && $_POST['action'] === 'insertorupdate') {
            $upload->setFullName($postdata['documento_solucion']);
            $upload->Upload();
        }
        if ($_POST['action'] === 'insertorupdate') {
            $postdata['fechahora_solucion'] = $fecha;
            if ($postdata['id_solucion'] === '' || $postdata['id_solucion'] === '0') {
                $postdata['id_solucion'] = $postdata['id_actividad'] + date('YmdHis') + rand(0, 30);
            }
            if ($postdata['id_estudiante'] === '' || $postdata['id_estudiante'] === '0') {
                $postdata['id_estudiante'] = $session->getUserID();
            }
            if ($session->getUserType() === 'Student') {
                unset($postdata['retroalimentacion_solucion']);
                unset($postdata['calificacion_solucion']);
            }
            $bc->setPostData($postdata);
            $bc->setAction('insertorupdate');
            $result = null;
            $result = $bc->execute(false);
        }

        if ($_POST['action'] === 'delete') {
            if ($postdata['id_solucion'] !== '' && $postdata['id_solucion'] !== '0') {
                $upload->Delete($postdata['documento_solucion']);
                $postdata['documento_solucion'] = '';
                $postdata['texto_solucion'] = '';
                $bc->setPostData($postdata);
                $bc->setAction('insertorupdate');
                $result = $bc->execute(false);
            }
        }

        if ($_POST['action'] === 'find') {
            if ($postdata['id_solucion'] !== '' && $postdata['id_solucion'] !== '0') {
                $bc->setAction('find');
                $result = $bc->execute(false);
            } else if ($postdata['id_actividad'] !== '' && $postdata['id_estudiante'] !== '') {
                $sql = "SELECT * FROM SolucionesActividadesVirtualesApp "
                        . " WHERE id_actividad=:p_id_actividad "
                        . " AND id_estudiante=:p_id_estudiante ";
                $arraywhere = array();
                $arraywhere['p_id_actividad'] = $postdata['id_actividad'];
                $arraywhere['p_id_estudiante'] = $postdata['id_estudiante'];
                $result = $bc->selectJSONArray($sql, $arraywhere);
                $result = $bc->parseResults($result, '', 1);
            }
        }

        echo $result;
        $bc->disconnect();
    }
}
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>