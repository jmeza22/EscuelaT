<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'DocentesApp';
$findBy = 'id_docente';
$action = 'insertorupdate';
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1)) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        if ($session->getStandard() == 1 || $session->getLimited() || $session->getExternal()) {
            unset($_POST['status_docente']);
        }
        if (isset($_POST['foto_docente']) && $_POST['foto_docente'] !== '') {
            if (isset($_FILES['image-file-photo']) && $_POST['action'] === 'insertorupdate') {
                $upload = new UploadImage();
                $upload->setURL('../../ImageFiles/');
                $upload->setFileName('image-file-photo');
                $upload->setFullName($_POST['foto_docente']);
                $upload->Upload();
            }
            if (!file_exists('../../ImageFiles/' . $_POST['foto_docente'])) {
                $_POST['foto_docente'] = '';
            }
        }
        if (isset($_POST['firma_docente']) && $_POST['firma_docente'] !== '') {
            if (isset($_FILES['image-file-sign']) && $_POST['action'] === 'insertorupdate') {
                $upload = new UploadImage();
                $upload->setURL('../../ImageFiles/');
                $upload->setFileName('image-file-sign');
                $upload->setFullName($_POST['firma_docente']);
                $upload->Upload();
            }
            if (!file_exists('../../ImageFiles/' . $_POST['firma_docente'])) {
                $_POST['firma_docente'] = '';
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