<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$crypt = new MyCrypt();
$model = "UsuariosApp";
$action = "update";
$findBy = "username_usuario";
$postdata = null;
if ($session->hasLogin() && $session->checkToken()) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $postdata = $bc->getPostData();
        $postdata['id_escuela'] = $session->getEnterpriseID();
        $postdata['username_usuario'] = $session->getNickname();
        if (isset($postdata['newpassword_usuario']) && strcmp($postdata['newpassword_usuario'], "") !== 0 && ($session->getAdmin() == 1 || $session->getSuperAdmin() == 1)) {
            $postdata['password_usuario'] = $crypt->crypt($postdata['password_usuario']);
            $postdata['newpassword_usuario'] = $crypt->crypt($postdata['newpassword_usuario']);
        } else {
            unset($postdata['newpassword_usuario']);
            unset($postdata['newpassword2_usuario']);
        }
        $sql = "SELECT * FROM UsuariosApp WHERE id_escuela=:p_id_escuela AND username_usuario=:p_username_usuario AND password_usuario=:p_password_usuario AND status_usuario=1 ";
        $arraywhere = array();
        $arraywhere['p_id_escuela'] = $postdata['id_escuela'];
        $arraywhere['p_username_usuario'] = $postdata['username_usuario'];
        $arraywhere['p_password_usuario'] = $postdata['password_usuario'];
        $bc->setModel($model);
        $bc->setAction($action);
        $bc->setFindBy($findBy);
        $user = $bc->selectAssocArray($sql, $arraywhere);
        if (isset($user[0])) {
            $user = $user[0];
            $postdata['password_usuario'] = $postdata['newpassword_usuario'];
        }else{
            unset($postdata['password_usuario']);
        }
        unset($postdata['newpassword_usuario']);
        unset($postdata['newpassword2_usuario']);
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

