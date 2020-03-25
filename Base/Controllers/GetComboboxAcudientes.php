<?php


include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$where = null;
$array1 = null;
$array2 = null;
$arraymerge = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('ObservadorEstudianteApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $arraywhere = $bc->parseFindByToArray($_POST);
    $arraywhere['status_estudiante'] = '1';
    $colname = 'nombreacudiente1_estudiante';
    $colvalue = 'nombreacudiente1_estudiante';
    $othervalue = 'idacudiente1_estudiante';
    $array1 = $bc->getComboboxData($colname, $colvalue, $othervalue, null, $arraywhere);
    $array1 = json_decode($array1, true);
    $colname = 'nombreacudiente2_estudiante';
    $colvalue = 'nombreacudiente2_estudiante';
    $othervalue = 'idacudiente2_estudiante';
    $array2 = $bc->getComboboxData($colname, $colvalue, $othervalue, null, $arraywhere);
    $array2 = json_decode($array2, true);
    $arraymerge = array_merge($array1, $array2);
    $arraymerge = json_encode($arraymerge);
    echo $arraymerge;
    $bc->disconnect();
}

?>