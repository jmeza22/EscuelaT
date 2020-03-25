<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'CargosEleccionesApp';
$findBy = 'id_cargo';
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
        $bc->setPostData($postdata);
        if (isset($_POST['action']) && $_POST['action'] === 'find') {
            $bc->setAction('find');
        }
        $result = $bc->execute(true);
        $result = null;
        $bc->executeSQL("DELETE FROM $model WHERE status_cargo=0 ");
        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}
?>
