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
if ($session->hasLogin() && $session->checkToken() && ($session->getUserType() === 'Student')) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        $postdata['fechahora_solucion'] = $fecha;
        $postdata['id_escuela'] = $session->getEnterpriseID();
        if ($_POST['id_estudiante'] === '' || $_POST['id_estudiante'] === '0') {
            $postdata['id_estudiante'] = $session->getUserID();
        }
        $bc->setPostData($postdata);
        if (isset($_POST['action']) && $_POST['action'] === 'find') {
            $bc->setAction('find');
        }
        $result = null;
        $result = $bc->execute(true);
        if (isset($_FILES['document-file']) && $_POST['action'] === 'insertorupdate') {
            $upload = new UploadDocument();
            $upload->setURL('../../DocumentFiles/');
            $upload->setFileName('document-file');
            $upload->setFullName($_POST['documento_solucion']);
            $upload->Upload();
        }
        $bc->disconnect();
    }
}
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>