<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'ObservadorEstudianteApp';
$findBy = 'id_estudiante';
$action = 'insertorupdate';
if ($session->hasLogin() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1)) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        if (isset($_POST['foto_estudiante']) && $_POST['foto_estudiante'] !== '') {
            if (isset($_FILES['image-file']) && $_POST['action'] === 'insertorupdate') {
                $upload = new UploadImage();
                $upload->setURL('../../ImageFiles/');
                $upload->setFileName('image-file');
                $upload->setFullName($_POST['foto_estudiante']);
                $upload->Upload();
            }
            if (!file_exists('../../ImageFiles/' . $_POST['foto_estudiante'])) {
                $_POST['foto_estudiante'] = '';
            }
        }
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        if (isset($_POST['action']) && $_POST['action'] === 'find') {
            $bc->setAction('find');
        }
        $result = null;
        $result = $bc->execute(true);
        $bc->disconnect();
    }
}
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>