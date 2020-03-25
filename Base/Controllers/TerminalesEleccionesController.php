<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'TerminalesEleccionesApp';
$findBy = 'id_terminal';
$action = 'insertorupdate';
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getManagement() == 1 )) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        $postdata['id_escuela'] = $session->getEnterpriseID();
        if ($postdata['password_terminal'] === '') {
            unset($postdata['password_terminal']);
        }
        $bc->setPostData($postdata);
        if (isset($_POST['action']) && $_POST['action'] === 'find') {
            $bc->setAction('find');
        }
        $result = $bc->execute(true);
        $bc->executeSQL("DELETE FROM $model WHERE status_terminal=0 ");
        $bc->disconnect();
    }
}
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>
