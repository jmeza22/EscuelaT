<?php

include_once 'Libraries/Controllers.php';
include_once 'Others/UploadDocument.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'ActividadesVirtualesApp';
$findBy = 'id_actividad';
$action = 'insertorupdate';
$postdata = null;
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getStandard() == 1)) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        $postdata['id_escuela'] = $session->getEnterpriseID();
        $postdata['id_docente'] = $session->getUserID();
        $postdata['fechadesde_actividad'] = str_replace('00:00:00', '00:00:01', $postdata['fechadesde_actividad']);
        $postdata['fechahasta_actividad'] = str_replace('00:00:00', '23:59:59', $postdata['fechahasta_actividad']);
        $bc->setPostData($postdata);
        if (isset($_POST['action']) && $_POST['action'] === 'find') {
            $bc->setAction('find');
        }
        $result = null;
        $result = $bc->execute(true);
        if (isset($_FILES['document-file']) && $_POST['action'] === 'insertorupdate' && $bc->getRowCount() > 0) {
            $upload = new UploadDocument();
            $upload->setURL('../../DocumentFiles/');
            $upload->setFileName('document-file');
            $upload->setFullName($_POST['documento_actividad']);
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