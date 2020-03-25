<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'ConfiguracionApp';
$findBy = 'id_escuela';
$action = 'insertorupdate';
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 )) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        if (isset($_POST['logo_configuracion']) && $_POST['logo_configuracion'] !== '') {
            if (!file_exists('../../ImageFiles/'.$_POST['logo_configuracion'])) {
                $_POST['logo_configuracion']='';
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
        $result = null;
        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}

?>