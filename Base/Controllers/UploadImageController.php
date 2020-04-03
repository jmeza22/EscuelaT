<?php

session_start();
include_once 'Security/SessionManager.php';
include_once 'Security/SetsAndHeaders.php';
include_once 'Others/UploadImage.php';
$session = new SessionManager();
$upload = null;
$result = null;
if ($session->hasLogin() && isset($_POST['prefix_image']) && isset($_POST['name_image'])) {
    $result = $session->getSessionStateJSON();
    $result = json_decode($result, true);
    $upload = new UploadImage();
    $upload->setURL('../../ImageFiles/');
    $upload->setFileName('image-file');
    if (isset($_POST['prefix_image']) && isset($_POST['name_image'])) {
        if ($_POST['prefix_image'] !== 'NULL' && $_POST['name_image'] !== 'NULL' && $_POST['prefix_image'] !== '' && $_POST['name_image'] !== '') {
            $upload->setPrefix($_POST['prefix_image']);
            $upload->setNewName($_POST['name_image']);
            $upload->setExtension('.jpg');
        }
    }
    if (isset($_POST['fullname_image'])) {
        $upload->setFullName($_POST['fullname_image']);
    }
    unset($result['data']);
    if ($upload->Upload()) {
        $result['message'] = 'La Imagen se ha Cargado con Exito!.';
        $result['status'] = 1;
    } else {
        $result['message'] = 'Hubo un Error al Cargar la Imagen!.';
        $result['status'] = 0;
    }
    $result = json_encode($result);
    echo $result;
} else {
    echo $session->getSessionStateJSON();
}
?>
