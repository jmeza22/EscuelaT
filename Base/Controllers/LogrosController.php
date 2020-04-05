<?php

include_once 'Libraries/Controllers.php';
$bc = null;
$result = null;
$session = new SessionManager();
$model = 'LogrosAsignaturasApp';
$findBy = 'id_logro';
$action = 'insertorupdate';
$postdata = null;
$fecha = date('YmdHis');
$find = null;
$numlogro = null;
$findByValue = null;
$sql = null;
if ($session->hasLogin() && $session->checkToken() && ($session->getStandard() == 1 || $session->getManagement() == 1 || $session->getAdmin() == 1 || $session->getSuperAdmin() == 1 || $session->getUserType() === 'Teacher')) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        $postdata['id_escuela'] = $session->getEnterpriseID();
        if (isset($_POST['action']) && $_POST['action'] !== null) {
            if (strcmp($_POST['action'], 'insertorupdate') === 0) {
                if (strcmp($_POST['id_logro'], '') === 0 || strcmp($_POST['id_logro'], '0') === 0) {
                    $postdata['id_logro'] = $fecha;
                    $postdata['num_logro'] = 0;
                    $bc->setAction('insertorupdate');
                }
            }
            if (strcmp($_POST['action'], 'find') === 0) {
                $bc->setAction('find');
            }
        }
        $bc->setPostData($postdata);
        $result = null;
        $result = $bc->execute(true);
        
        $sql = "UPDATE $model SET id_logro=CONCAT('L',num_logro) WHERE id_logro='" . $fecha . "' ";
        $bc->executeSQL($sql);
        $bc->disconnect();
    }
} 
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>
