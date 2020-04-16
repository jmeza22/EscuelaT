<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'EventosApp';
$findBy = 'id_evento';
$action = 'insertorupdate';
$postdata = null;
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1)) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        $postdata['id_escuela'] = $session->getEnterpriseID();
        if (isset($postdata['id_programa']) && $postdata['id_programa'] === '') {
            unset($postdata['id_programa']);
        }
        if (isset($_POST['action']) && $_POST['action'] === 'find') {
            $bc->setAction('find');
        }
        $bc->setPostData($postdata);
        $result = null;
        $result = $bc->execute(true);
        $result = null;
        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}
?>