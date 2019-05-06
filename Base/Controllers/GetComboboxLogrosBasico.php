<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$where = null;
if ($session->hasLogin() && $_REQUEST != null && isset($_REQUEST)) {
    $bc = new BaseController();
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
    $where = " status_logro=1 and tipo_logro='DB' and id_escuela=" . $session->getEnterpriseID() . " ";
    if (isset($_REQUEST['findby']) && isset($_REQUEST['findbyvalue']) && isset($_REQUEST['findby2']) && isset($_REQUEST['findbyvalue2']) && strcmp($_REQUEST['findbyvalue'], '') !== 0 && strcmp($_REQUEST['findbyvalue2'], '') !== 0) {
        $where = $where . " and " . $_REQUEST['findby'] . " = " . $_REQUEST['findbyvalue'] . " and " . $_REQUEST['findby2'] . " = " . $_REQUEST['findbyvalue2'] . " ";
    }
    //echo $where;
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, $where);
    $bc->disconnect();
}
ob_end_flush();
?>