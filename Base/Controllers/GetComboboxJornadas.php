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
    $bc->setModel('JornadasApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colvalue = 'id_jornada';
    $colname = "nombre_jornada";
    $othervalue = 'id_escuela';
    $arraywhere = $bc->parseFindByToArray($_POST);
    $arraywhere['status_jornada'] = '1';
    $arraywhere['id_escuela'] = ''.$session->getEnterpriseID();
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, null, $arraywhere);
    $bc->disconnect();
}

?>