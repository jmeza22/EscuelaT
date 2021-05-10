<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();

$bc = null;
$result = null;
$model = 'OVASApp';
$findBy = 'id_ova';
$action = 'insertorupdate';
$postdata = null;
if ($session->hasLogin() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1)) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $fecha = date('YmdHis');
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        if (isset($postdata[$findBy]) && ($postdata[$findBy] === 0 || $postdata[$findBy] === '')) {
            $postdata['id_escuela'] = $session->getEnterpriseID();
            $postdata['id_autor'] = $session->getUserID();
        }
        $bc->setPostData($postdata);
        if (isset($_POST['action']) && $_POST['action'] === 'find') {
            $bc->setAction('find');
        }

        if (isset($_FILES['documentfile']) && $_FILES['documentfile']['error'] ===0 && $_POST['action'] === 'insertorupdate') {
            $upload = new UploadDocument();
            $upload->setURL('../../DocumentFiles/');
            $upload->Delete($postdata['pdf_ova']);
            $upload->setFileName('documentfile');
            $upload->setPrefix('doc1OVA_');
            $name = $fecha . rand(0, 1000);
            $upload->setNewName($name);
            if ($upload->Upload()) {
                $postdata['pdf_ova'] = $upload->getOutputName();
            }
        }
        if (isset($_FILES['imagefile1']) && $_FILES['imagefile1']['error'] ===0 && $_POST['action'] === 'insertorupdate') {
            $upload = new UploadImage();
            $upload->setURL('../../ImageFiles/');
            $upload->Delete($postdata['imagen1_ova']);
            $upload->setFileName('imagefile1');
            $upload->setPrefix('img1OVA_');
            $name = $fecha . rand(0, 1000);
            $upload->setNewName($name);
            if ($upload->Upload()) {
                $postdata['imagen1_ova'] = $upload->getOutputName();
            }
        }
        if (isset($_FILES['imagefile2']) && $_FILES['imagefile2']['error'] ===0 && $_POST['action'] === 'insertorupdate') {
            $upload = new UploadImage();
            $upload->setURL('../../ImageFiles/');
            $upload->Delete($postdata['imagen2_ova']);
            $upload->setFileName('imagefile2');
            $upload->setPrefix('img2OVA_');
            $name = $fecha . rand(0, 1000);
            $upload->setNewName($name);
            if ($upload->Upload()) {
                $postdata['imagen2_ova'] = $upload->getOutputName();
            }
        }
        if (isset($_FILES['imagefile3']) && $_FILES['imagefile3']['error'] ===0 && $_POST['action'] === 'insertorupdate') {
            $upload = new UploadImage();
            $upload->setURL('../../ImageFiles/');
            $upload->Delete($postdata['imagen3_ova']);
            $upload->setFileName('imagefile3');
            $upload->setPrefix('img3OVA_');
            $name = $fecha . rand(0, 1000);
            $upload->setNewName($name);
            if ($upload->Upload()) {
                $postdata['imagen3_ova'] = $upload->getOutputName();
            }
        }
        if (isset($_FILES['imagefile4']) && $_FILES['imagefile4']['error'] ===0 && $_POST['action'] === 'insertorupdate') {
            $upload = new UploadImage();
            $upload->setURL('../../ImageFiles/');
            $upload->Delete($postdata['imagen4_ova']);
            $upload->setFileName('imagefile4');
            $upload->setPrefix('img4OVA_');
            $name = $fecha . rand(0, 1000);
            $upload->setNewName($name);
            if ($upload->Upload()) {
                $postdata['imagen4_ova'] = $upload->getOutputName();
            }
        }
        if (isset($_FILES['audiofile1']) && $_FILES['audiofile1']['error'] ===0 && $_POST['action'] === 'insertorupdate') {
            $upload = new UploadAudio();
            $upload->setURL('../../AudioFiles/');
            $upload->Delete($postdata['audio1_ova']);
            $upload->setFileName('audiofile1');
            $upload->setPrefix('aud1OVA_');
            $name = $fecha . rand(0, 1000);
            $upload->setNewName($name);
            if ($upload->Upload()) {
                $postdata['audio1_ova'] = $upload->getOutputName();
            }
        }
        if (isset($_FILES['audiofile2']) && $_FILES['audiofile2']['error'] ===0 && $_POST['action'] === 'insertorupdate') {
            $upload = new UploadAudio();
            $upload->setURL('../../AudioFiles/');
            $upload->Delete($postdata['audio2_ova']);
            $upload->setFileName('audiofile2');
            $upload->setPrefix('aud2OVA_');
            $name = $fecha . rand(0, 1000);
            $upload->setNewName($name);
            if ($upload->Upload()) {
                $postdata['audio2_ova'] = $upload->getOutputName();
            }
        }
        //print_r($postdata);
        $bc->setPostData($postdata);

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