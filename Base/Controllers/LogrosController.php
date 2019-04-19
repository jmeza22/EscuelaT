<?php

ob_start();
include_once 'Libraries/Controllers.php';
$bc = null;
$result = null;
$session = new SessionManager();
$model = 'LogrosAsignaturasApp';
$findBy = 'id_logro';
$action = 'insertorupdate';
$postdata = null;
if ($session->hasLogin() && ($session->getStandard()==1 || $session->getManagement()==1 || $session->getAdmin()==1 || $session->getSuperAdmin()==1)) {
    if (isset($_POST) && $_POST != null) {
        $bc = new BaseController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
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
ob_end_flush();
?>