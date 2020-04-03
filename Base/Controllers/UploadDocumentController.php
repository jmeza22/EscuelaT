<?php

session_start();
include_once 'Security/SessionManager.php';
include_once 'Security/SetsAndHeaders.php';
include_once 'Others/UploadDocument.php';
$session = new SessionManager();
$upload = null;
$result = null;
if ($session->hasLogin() && isset($_POST['prefix_document']) && isset($_POST['name_document'])) {
    $result = $session->getSessionStateJSON();
    $result = json_decode($result, true);
    $upload = new UploadDocument();
    $upload->setURL('../../DocumentFiles/');
    $upload->setFileName('document-file');
    if (isset($_POST['prefix_document']) && isset($_POST['name_document'])) {
        if ($_POST['prefix_document'] !== '' && $_POST['name_document'] !== '' && $_POST['prefix_document'] !== 'NULL' && $_POST['name_document'] !== 'NULL') {
            $upload->setPrefix($_POST['prefix_document']);
            $upload->setNewName($_POST['name_document']);
        }
    }
    if (isset($_POST['fullname_document'])) {
        $upload->setFullName($_POST['fullname_document']);
    }
    unset($result['data']);
    if ($upload->Upload()) {
        $result['message'] = 'El Documento se ha Cargado con Exito!.';
        $result['status'] = 1;
    } else {
        $result['message'] = 'Hubo un Error al Cargar el Documento!.';
        $result['status'] = 0;
    }
    $result = json_encode($result);
    echo $result;
} else {
    echo $session->getSessionStateJSON();
}
?>
