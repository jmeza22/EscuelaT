<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'AsignaturasApp';
$findBy = 'id_asignatura';
$action = 'insertorupdate';
if ($session->hasLogin() && $session->checkToken() && $session->getSuperAdmin() == 1) {
    if (isset($_POST) && $_POST != null) {
        $bc = new BaseController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        $postdata['id_escuela']=$session->getEnterpriseID();
        $bc->setPostData($postdata);
        if (isset($_POST['action']) && $_POST['action'] !== null && strcmp($_POST['action'], 'find') === 0) {
            $bc->setAction('find');
        }
        $result = $bc->execute(true);
        $result = null;
        $bc->executeSQL("DELETE FROM $model WHERE status_asignatura=0 ");
        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}

?>
