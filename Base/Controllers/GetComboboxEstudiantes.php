<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$where = null;
if ($session->hasLogin() && $_POST != null && isset($_POST)) {
    $bc = new BaseController();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('ObservadorEstudianteApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colvalue = 'id_estudiante';
    $colname = "nombrecompleto_estudiante";
    $othervalue = 'nombreacudiente1_estudiante';
    $where = ' status_estudiante=1 ';
    if (isset($_POST['findby']) && isset($_POST['findbyvalue']) && strcmp($_POST['findbyvalue'], '') !== 0) {
        $where = $where . " and " . $_POST['findby'] . " = " . $_POST['findbyvalue'] . "";
    }
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, $where);
    $bc->disconnect();
}
ob_end_flush();
?>