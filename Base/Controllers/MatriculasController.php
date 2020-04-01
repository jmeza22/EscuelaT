<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
include_once 'Classes/Matriculas.php';
$session = new SessionManager();
$bc = null;
$result = null;
$findBy = 'id_matricula';
$action = 'insertorupdate';
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1)) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $action = $_POST['action'];
        $bc = new Matriculas();
        $bc->setArray($_POST);
        if (isset($action)) {
            if ($action === 'insertorupdate') {
                $result = $bc->insertMatricula();
                if ($bc->getRowCount() > 0) {
                    $bc->updateOtrasTablas();
                }
            }
            if ($action === 'find') {
                $result = $bc->findMatricula();
            }
            if ($action === 'update') {
                $result = $bc->updateMatricula();
            }
            if ($action === 'delete') {
                $result = $bc->deleteMatricula();
            }
        }
        $bc->disconnect();
    }
    echo $result;
}
if ($result === null) {
    echo $session->getSessionStateJSON();
}
?>