<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
include_once 'Classes/MatriculaAsignaturas.php';
$session = new SessionManager();
$bc = null;
$result = null;
$findBy = 'id_matricula';
$action = 'insertorupdate';
$automatic = false;
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1)) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $action = $_POST['action'];
        if (isset($_POST['automatic'])) {
            $automatic = $_POST['automatic'];
            unset($_POST['automatic']);
        }
        $bc = new MatriculaAsignaturas();
        $bc->setArray($_POST);
        if (isset($action)) {
            if ($automatic === 'true') {
                $result = $bc->insertAuto();
            }
            if ($action === 'insertorupdate') {
                $result = $bc->insertMatriculaAsignaturas();
            }
            if ($action === 'find') {
                $result = $bc->findMatriculaAsignaturas();
            }
            if ($action === 'update') {
                $result = $bc->updateMatriculaAsignaturas();
            }
            if ($action === 'delete') {
                $result = $bc->deleteMatriculaAsignaturas();
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