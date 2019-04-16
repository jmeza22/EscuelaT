<?php
ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'EscuelasApp';
$findBy = 'id_escuela';
$action = 'insertorupdate';
if ($session->hasLogin() && $session->getSuperAdmin() == 1) {
    if (isset($_POST) && $_POST != null) {
        $bc = new BaseController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        if (isset($_POST['action']) && $_POST['action'] !== null && strcmp($_POST['action'], 'find') === 0) {
            $bc->setAction('find');
        }
        $result = $bc->execute(true);
        $result = null;
        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}
ob_end_flush();
?>
