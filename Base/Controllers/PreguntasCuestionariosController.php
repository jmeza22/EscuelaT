<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'PreguntasCuestionariosApp';
$findBy = 'id_pregunta';
$action = 'insertorupdate';
if ($session->hasLogin() && $session->checkToken() && ($session->getAdmin() == 1 || $session->getSuperAdmin() == 1)) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        if (isset($_POST['id_lectura']) && $_POST['id_lectura'] === '') {
            unset($_POST['id_lectura']);
        }
        if (isset($_POST['imagen_pregunta']) && $_POST['imagen_pregunta'] !== '') {
            if (!file_exists('../../ImageFiles/'.$_POST['imagen_pregunta'])) {
                $_POST['imagen_pregunta']='';
            }
        }
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
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