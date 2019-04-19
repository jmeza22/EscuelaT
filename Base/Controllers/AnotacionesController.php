<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'AnotacionesObservadorApp';
$findBy = 'id_anotacion';
$action = 'insertorupdate';
if ($session->hasLogin() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1)) {
    if (isset($_POST) && $_POST != null) {
        if ($session->getStandard() == 1 || $session->getLimited() || $session->getExternal()) {
            unset($_POST['status_anotacion']);
        }
        $bc = new BaseController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        if (isset($_POST['action']) && strcmp($_POST['action'], 'find') === 0) {
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
ob_end_flush();
?>