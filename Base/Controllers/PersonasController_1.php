<?php

include_once 'Libraries/Controllers.php';
include_once 'Classes/Personas.php';
$session = new SessionManager();
$persona = null;
$rowcount = 0;
$message='';

if ($session->hasLogin() && $session->checkToken() && ($session->getStandard() == 1 || $session->getManagement() == 1 || $session->getAdmin() == 1 || $session->getSuperAdmin() == 1)) {
    $findBy='id_persona';
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $persona = new Personas();
        if (is_array($_POST[$findBy])) {
            $postdata = $persona->parseMultiRows($_POST);
            $count = count($postdata);
            for ($i = 0; $i < $count; $i++) {
                $persona = new Personas($postdata[$i]);
                $result = $persona->insertPersona();
                if ($persona->getRowCount() > 0) {
                    $rowcount++;
                } else {
                    $message=$message.$postdata[$i][$findBy].': '.$persona->getErrorMessage().' \r\n';
                }
                $persona->insertUsuario();
                if (isset($postdata[$i]['id_tipousuario']) && $postdata[$i]['id_tipousuario'] !== '') {
                    if ($postdata[$i]['id_tipousuario'] === 'Student') {
                        $persona->insertEstudiante();
                    }
                    if ($postdata[$i]['id_tipousuario'] === 'Teacher') {
                        $persona->insertDocente();
                    }
                }
            }
        }
        $persona->disconnect();
        echo $result;
    }
}
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>
