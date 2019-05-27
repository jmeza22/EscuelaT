<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'PersonasApp';
$findBy = 'id_persona';
$action = 'insertorupdate';
$idpersona = null;
$idtipousuario = null;
if ($session->hasLogin() && $session->checkToken() && ($session->getStandard() == 1 || $session->getManagement() == 1 || $session->getAdmin() == 1 || $session->getSuperAdmin() == 1)) {
    if (isset($_POST) && $_POST != null) {
        if (isset($_POST['id_tipousuario']) && $_POST['id_tipousuario'] !== null) {
            $idtipousuario = $_POST['id_tipousuario'];
            unset($_POST['id_tipousuario']);
        }
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
        if ($bc->getRowCount() > 0) {
            $sql = "UPDATE $model SET id_persona=CONCAT('P',num_persona) WHERE id_persona='" . $bc->getPostData()[$findBy] . "' ";
            $bc->executeSQL($sql);
            $sql = "DELETE FROM $model WHERE status_persona=0 ";
            $bc->executeSQL($sql);
        }
        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}
ob_end_flush();
?>
