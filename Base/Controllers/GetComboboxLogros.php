<?php

ob_start();
include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$where = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('LogrosAsignaturasApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colvalue = 'id_logro';
    $colname = " concat('(',id_logro,') ',descripcion_logro) ";
    $othervalue = 'tipo_logro';
    $where = " status_logro=1 and id_escuela='" . $session->getEnterpriseID() . "' ";
    if (isset($_POST['findby']) && isset($_POST['findbyvalue']) && isset($_POST['findby2']) && isset($_POST['findbyvalue2']) && strcmp($_POST['findbyvalue'], '') !== 0 && strcmp($_POST['findbyvalue2'], '') !== 0) {
        $where = $where . " and " . $_POST['findby'] . " = '" . $_POST['findbyvalue'] . "' and " . $_POST['findby2'] . " = '" . $_POST['findbyvalue2'] . "' ";
    }
    //echo $where;
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, $where);
    $bc->disconnect();
}
ob_end_flush();
?>