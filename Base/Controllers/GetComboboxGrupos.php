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
    $bc->setModel('GruposApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colvalue = 'id_grupo';
    $colname = "CONCAT(numgrado_programa, num_grupo)";
    $othervalue = 'id_escuela';
    $where = 'status_grupo=1 and id_escuela=' . $session->getEnterpriseID() . ' ';
    if (isset($_POST['findby']) && isset($_POST['findbyvalue']) && strcmp($_POST['findbyvalue'], '') !== 0) {
        $where = $where . " and " . $_POST['findby'] . " = " . $_POST['findbyvalue'] . " ";
    }
    $where = $where . " ORDER BY CAST(numgrado_programa AS DECIMAL), num_grupo";
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, $where);
    $bc->disconnect();
}
ob_end_flush();
?>