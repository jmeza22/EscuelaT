<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$where = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('PeriodosAnualesApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colname = 'id_periodo';
    $colvalue = 'id_periodo';
    $othervalue = 'anualidad_periodo';
    $arraywhere = $bc->parseFindByToArray($_POST);
    $arraywhere['status_periodo'] = '1';
    $arraywhere['cerrado_periodo'] = '0';
    $arraywhere['id_escuela'] = '' . $session->getEnterpriseID();
    $where = " id_escuela = '" . $session->getEnterpriseID() . "' AND cerrado_periodo=0 AND status_periodo=1 ";
    if (isset($arraywhere['anualidad_periodo_old'])) {
        $where = $where . " AND anualidad_periodo >= " . $arraywhere['anualidad_periodo_old'];
    }
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, $where, null);
    $bc->disconnect();
}
?>