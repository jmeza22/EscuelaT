<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$bc = null;
$where = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel('EleccionesEstudiantilesApp');
    $bc->setAction('findAll');
    $colname = null;
    $colvalue = null;
    $othervalue = null;
    $colvalue = "id_eleccion";
    $colname = "CONCAT(id_eleccion,'-',id_periodo) ";
    $othervalue = "id_periodo";
    $arraywhere = $bc->parseFindByToArray($_POST);
    $arraywhere['status_eleccion'] = '1';
    $arraywhere['id_escuela'] = '' . $session->getEnterpriseID();
    if ($variables->getIdPeriodoAnual() !== null) {
        $arraywhere['id_periodo'] = '' . $variables->getIdPeriodoAnual();
    }
    echo $bc->getComboboxData($colname, $colvalue, $othervalue, null, $arraywhere);
    $bc->disconnect();
}
?>