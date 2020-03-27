<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
include_once 'Classes/Personas.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'PersonasApp';
$findBy = 'id_persona';
if ($session->hasLogin() && $session->checkToken() && ($session->getStandard() == 1 || $session->getManagement() == 1 || $session->getAdmin() == 1 || $session->getSuperAdmin() == 1)) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $persona = new Personas();
        $persona->setArray($_POST);
        $idtipousuario = null;
        if (isset($_POST['id_tipousuario']) && $_POST['id_tipousuario'] !== null) {
            $idtipousuario = $_POST['id_tipousuario'];
            unset($_POST['id_tipousuario']);
        }
        if (isset($_POST['action']) && $_POST['action'] === 'find') {
            $result = $persona->findPersona();
        }
        if (isset($_POST['action']) && $_POST['action'] === 'insertorupdate') {
            $result = $persona->insertPersona();
            if ($persona->getRowCount() > 0 && isset($idtipousuario) && $idtipousuario !== '') {
                $persona->insertUsuario();
                if ($idtipousuario === 'Student') {
                    $persona->insertEstudiante();
                }
                if ($idtipousuario === 'Teacher') {
                    $persona->insertDocente();
                }
            }
        }
        if (isset($_POST['action']) && ($_POST['action'] === 'update' || $_POST['action'] === 'delete')) {
            $result = $persona->updatePersona();
        }
        echo $result;
        $persona->disconnect();
    }
}
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>
