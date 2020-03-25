<?php

include_once 'Libraries/Controllers.php';
$bc = null;
$result = null;
$session = new SessionManager();
$model = 'JornadasApp';
$findBy = 'id_jornada';
$action = 'insertorupdate';
$postdata = null;
if ($session->hasLogin() && $session->checkToken() && ($session->getAdmin() == 1 || $session->getSuperAdmin() == 1)) {
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
        $bc->disconnect();
    }
}
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>
