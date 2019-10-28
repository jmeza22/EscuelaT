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
    $bc->setModel('DocentesApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colvalue = 'id_docente';
    $colname = "nombrecompleto_docente";
    $othervalue = 'ultimotitulo_docente';
    $arraywhere = $bc->parseFindByToArray($_POST);
    $arraywhere['status_docente'] = '1';
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, null, $arraywhere);
    $bc->disconnect();
}

?>