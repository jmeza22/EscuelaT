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
    $bc->setModel('GruposApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colvalue = "id_grupo";
    $colname = "CONCAT(numgrado_programa, num_grupo)";
    $othervalue = "id_escuela";
    $arraywhere = $bc->parseFindByToArray($_POST);
    $arraywhere['status_grupo'] = '1';
    $arraywhere['id_escuela'] = ''.$session->getEnterpriseID();
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, null, $arraywhere);
    $bc->disconnect();
}

?>