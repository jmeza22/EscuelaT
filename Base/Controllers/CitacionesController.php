<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'CitacionesObservadorApp';
$findBy = 'id_citacion';
$action = 'insertorupdate';
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1)) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        if ($session->getStandard() == 1 || $session->getLimited() || $session->getExternal()) {
            unset($_POST['status_citacion']);
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
        $bc->executeSQL("DELETE FROM $model WHERE status_citacion=0 ");
        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}

?>