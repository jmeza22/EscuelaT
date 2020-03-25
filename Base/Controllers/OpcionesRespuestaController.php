<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'OpcionesRespuestaApp';
$findBy = 'id_opcionrespuesta';
$action = 'insertorupdate';
if ($session->hasLogin() && $session->checkToken() && ($session->getAdmin() == 1 || $session->getSuperAdmin() == 1)) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        if (isset($_POST['action']) && $_POST['action'] !== null && $_POST['action'] === 'find') {
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