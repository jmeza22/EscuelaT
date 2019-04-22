<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$where = null;
if ($session->hasLogin() && isset($_POST) && $_POST != null ) {
    $bc = new BaseController();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('PeriodosAnualesApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colname = 'anualidad_periodo';
    $colvalue = 'id_periodo';
    $othervalue = 'id_escuela';
    $where = "status_periodo=1 and id_escuela = " . $session->getEnterpriseID() . "";
    if (isset($_POST['findby']) && isset($_POST['findbyvalue']) && strcmp($_POST['findbyvalue'], '') !== 0) {
        $where = $where . " and " . $_POST['findby'] . " = " . $_POST['findbyvalue'] . "";
    }
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, $where);
    $bc->disconnect();
}
ob_end_flush();
?>