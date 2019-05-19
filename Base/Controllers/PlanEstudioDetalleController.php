<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'PlanEstudioDetalleApp';
$findBy = 'id_planestudiodetalle';
$action = 'insertorupdate';
if ($session->hasLogin() && ($session->getAdmin() == 1 || $session->getSuperAdmin() == 1)) {
    if (isset($_POST) && $_POST != null) {
        $bc = new BaseController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        if (isset($_POST['action']) && strcmp($_POST['action'], 'find') === 0) {
            $bc->setAction('find');
        }
        $result = null;
        $result = $bc->execute(true);
        $result = null;
        $bc->executeSQL("DELETE FROM $model WHERE status_planestudiodetalle=0 ");
        $bc->disconnect();
        
    }
} else {
    echo $session->getSessionStateJSON();
}
ob_end_flush();
?>