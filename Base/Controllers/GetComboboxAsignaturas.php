<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$where = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new BaseController();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('AsignaturasApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colvalue = 'id_asignatura';
    $colname = 'nombre_asignatura';
    $othervalue = 'id_area';
    $where = 'status_asignatura=1 and id_escuela=' . $session->getEnterpriseID() . ' ';
    if (isset($_POST['findby']) && isset($_POST['findbyvalue']) && strcmp($_POST['findbyvalue'], '') !== 0) {
        $where = $where . " and " . $_POST['findby'] . " = " . $_POST['findbyvalue'] . "";
    }
    $where = $where . " ORDER BY CAST(id_asignatura AS DECIMAL)";
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, $where);
    $bc->disconnect();
}
ob_end_flush();
?>