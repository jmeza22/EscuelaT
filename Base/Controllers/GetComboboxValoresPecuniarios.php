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
    $bc->setModel('ValoresPecuniariosApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colvalue = 'id_pecuniario';
    $colname = "tipo_pecuniario";
    $othervalue = 'valor_pecuniario';
    $arraywhere = $bc->parseFindByToArray($_POST);
    $arraywhere['status_pecuniario'] = '1';
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, null, $arraywhere);
    $bc->disconnect();
}

?>