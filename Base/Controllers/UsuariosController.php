<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$crypt = new MyCrypt();
$model = "UsuariosApp";
$action = "insertorupdate";
$postdata = null;
if ($session->hasLogin() && $session->checkToken() && ($session->getManagement()==1 || $session->getAdmin()==1 || $session->getSuperAdmin()==1)) {
    if (isset($_POST) && $_POST != null) {
        $bc = new BaseController();
        $bc->connect();
        $bc->preparePostData();
        $postdata = $bc->getPostData();
        $postdata['id_escuela']=$session->getEnterpriseID();
        if (isset($postdata['password_usuario']) && strcmp($postdata['password_usuario'], "") !== 0) {
            $postdata['password_usuario'] = $crypt->crypt($postdata['password_usuario']);
        }
        if (strcmp($postdata['password_usuario'], "") === 0) {
            unset($postdata['password_usuario']);
        }
        $bc->setModel($model);
        $bc->setAction($action);
        if (strcmp($_POST['action'], 'find') === 0) {
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
ob_end_flush();
?>

