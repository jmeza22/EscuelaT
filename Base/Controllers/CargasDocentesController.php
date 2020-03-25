<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'CargasDocentesApp';
$findBy = 'id_carga';
$action = 'insertorupdate';
$postdata = null;
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1)) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        $postdata['id_escuela'] = $session->getEnterpriseID();
        $bc->setPostData($postdata);
        if (isset($_POST['action']) && $_POST['action'] === 'find') {
            $bc->setAction('find');
        }
        $result = null;
        $result = $bc->execute(true);
        $bc->executeSQL("DELETE FROM $model WHERE status_carga=0 ");
        $bc->disconnect();
    }
}
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>