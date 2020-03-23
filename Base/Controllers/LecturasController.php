<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'LecturasApp';
$findBy = 'id_lectura';
$action = 'insertorupdate';
$postdata = null;
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() || $session->getStandard())) {
    if (isset($_POST) && $_POST != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        $postdata['id_escuela']=$session->getEnterpriseID();
        $postdata['usuario_crea']=$session->getNickname();
        $postdata['fechahoraedita_lectura']=date('Y-m-d H:i:s');
        $bc->setPostData($postdata);
        if (isset($_POST['action']) && $_POST['action'] !== null && strcmp($_POST['action'], 'find') === 0) {
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