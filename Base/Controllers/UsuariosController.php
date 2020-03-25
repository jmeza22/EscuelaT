<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$crypt = new MyCrypt();
$model = "UsuariosApp";
$action = "insertorupdate";
$postdata = null;
if ($session->hasLogin() && $session->checkToken() && ($session->getManagement() == 1 || $session->getAdmin() == 1 || $session->getSuperAdmin() == 1)) {
    if (isset($_POST['username_usuario']) && $_POST['username_usuario'] != null) {
        $bc = new BasicController();
        $bc->connect();
        if (strcmp($_POST['action'], 'find') === 0) {
            $action='find';
        }
        $bc->preparePostData();
        $postdata = $bc->getPostData();
        $postdata['id_escuela'] = $session->getEnterpriseID();
        if (isset($postdata['password_usuario']) && strcmp($postdata['password_usuario'], "") !== 0 && ($session->getAdmin() == 1 || $session->getSuperAdmin() == 1)) {
            $postdata['password_usuario'] = $crypt->crypt($postdata['password_usuario']);
        } else {
            unset($postdata['password_usuario']);
        }
        $bc->setModel($model);
        $bc->setAction($action);
        
        $bc->setPostData($postdata);
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

