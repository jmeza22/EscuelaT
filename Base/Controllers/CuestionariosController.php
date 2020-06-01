<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'CuestionariosApp';
$findBy = 'id_cuestionario';
$action = 'insertorupdate';
$postdata = null;
if ($session->hasLogin() && $session->checkToken()) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        $postdata['id_escuela'] = $session->getEnterpriseID();
        if ($postdata[$findBy] === '' || $postdata[$findBy] === '0') {
            $postdata[$findBy] = 'CU' . date('YmdHis') . rand(10, 99);
            $postdata['usuario_crea'] = $session->getNickname();
        }
        $postdata['fechahoraedita_cuestionario'] = date('Y-m-d H:i:s');
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